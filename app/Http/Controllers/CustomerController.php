<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Customer;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;  

class CustomerController extends Controller
{
    // -------------------------------------------------------
    // Tampilkan halaman pemesanan
    // -------------------------------------------------------
    public function index()
    {
        // Ambil semua vendor untuk dropdown level 1
        $vendors = Vendor::orderBy('nama_vendor')->get();
        return view('customer.index', compact('vendors'));
    }

    // EDIT - tampilkan form edit
public function editCustomer($id)
{
    $customer  = Customer::findOrFail($id);
    $provinsi  = \App\Models\Provinsi::orderBy('name')->get();

    // Load kota, kecamatan, kelurahan sesuai data customer yang ada
    $kota      = $customer->kota
                    ? \App\Models\Kota::where('province_id', $customer->provinsi)->orderBy('name')->get()
                    : collect();
    $kecamatan = $customer->kecamatan
                    ? \App\Models\Kecamatan::where('regency_id', $customer->kota)->orderBy('name')->get()
                    : collect();
    $kelurahan = $customer->kelurahan
                    ? \App\Models\Kelurahan::where('district_id', $customer->kecamatan)->orderBy('name')->get()
                    : collect();

    return view('customer.edit', compact('customer', 'provinsi', 'kota', 'kecamatan', 'kelurahan'));
}

// UPDATE - simpan perubahan
public function updateCustomer(Request $request, $id)
{
    $request->validate([
        'nama'      => 'required|string|max:255',
        'alamat'    => 'nullable|string',
        'provinsi'  => 'nullable|string|max:100',
        'kota'      => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kelurahan' => 'nullable|string|max:100',
        'kode_pos'  => 'nullable|string|max:10',
    ]);

    $customer = Customer::findOrFail($id);
    $customer->update($request->only([
        'nama','alamat','provinsi','kota',
        'kecamatan','kelurahan','kode_pos'
    ]));

    return redirect()->route('customer.data')
                     ->with('success', 'Customer berhasil diupdate!');
}

// DELETE
public function destroyCustomer($id)
{
    Customer::findOrFail($id)->delete();
    return redirect()->route('customer.data')
                     ->with('success', 'Customer berhasil dihapus!');
}


    // -------------------------------------------------------
    // AJAX: ambil menu berdasarkan vendor yang dipilih
    // -------------------------------------------------------
    public function getMenu(Request $request)
    {
        $menu = Menu::where('idvendor', $request->idvendor)
                    ->orderBy('nama_menu')
                    ->get();

        return response()->json([
            'status' => 'success',
            'code'   => 200,
            'data'   => $menu
            // $menu (array PHP) otomatis diubah ke format JSON
            // dan dikirim balik ke browser sebagai HTTP response, MASUK KE SUCCESS

// JSON yang dikirim balik ke browser:
// {
//   "status": "success",
//   "code": 200,
//   "data": [
//     { "idmenu": 1, "nama_menu": "Ayam Geprek", "harga": 18000, ... },
//     { "idmenu": 3, "nama_menu": "Es Teh Manis", "harga": 5000, ... },
//     ...
//   ]
// }
        ]);
    }

    // -------------------------------------------------------
    // Proses checkout:
    // 1. Generate nama Guest otomatis
    // 2. Simpan pesanan ke database
    // 3. Minta Snap Token ke Midtrans
    // -------------------------------------------------------
    public function checkout(Request $request) 
    {
        try {

        $request->validate([
            'items' => 'required|array|min:1',
            'total' => 'required|integer|min:1',
        ]);

        // Generate nama Guest otomatis: Guest_0000001, Guest_0000002, dst
        // Cari pesanan terakhir untuk tahu nomor selanjutnya
        $lastPesanan = Pesanan::latest('idpesanan')->first();
        $nextNumber  = $lastPesanan ? ($lastPesanan->idpesanan + 1) : 1;
        $namaGuest   = 'Guest_' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        // ID unik untuk Midtrans 
        $orderId = 'KANTIN-' . time() . '-' . rand(100, 999);

        // Simpan header pesanan ke db dulu
        $pesanan = Pesanan::create([
            'nama'              => $namaGuest,
            'timestamp'         => Carbon::now(),
            'total'             => $request->total,
            'metode_bayar'      => 'QRIS',
            'status_bayar'      => 0, // belum bayar
            'midtrans_order_id' => $orderId,
            // midtrans_order_id disimpan agar nanti saat webhook datang
            // kita bisa cari pesanan mana yang dimaksud
        ]);

        // Simpan setiap item detail
        foreach ($request->items as $item) {
            DetailPesanan::create([
                'idpesanan' => $pesanan->idpesanan,
                'idmenu'    => $item['idmenu'],
                'jumlah'    => $item['jumlah'],
                'harga'     => $item['harga'],
                'subtotal'  => $item['subtotal'],
                'catatan'   => $item['catatan'] ?? '',
                'timestamp' => Carbon::now(),
            ]);
        }

        // Setup Midtrans
        // Server Key = identitas akun kamu di Midtrans
        // Setiap request yang pakai Server Key ini masuk ke akun kamu
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

        // Parameter yang dikirim ke Midtrans
        // Midtrans simpan transaksi ini di akun sandbox 
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,   // ID transaksi yang KANTIN-XXXXXX
                'gross_amount' => $request->total, // total harus sama!
            ],
            'customer_details' => [
                'first_name' => $namaGuest,
            ],
            // Batasi hanya QRIS
            'enabled_payments' => ['gopay', 'qris'],
            // Detail item untuk ditampilkan di halaman Midtrans
            'item_details' => array_map(function($item) {
                return [
                    'id'       => $item['idmenu'],
                    'price'    => $item['harga'],
                    'quantity' => $item['jumlah'],
                    'name'     => $item['nama_menu'],
                ];
            }, $request->items),
        ];

        // Minta Snap Token ke Midtrans
        // DISINI kita ngasih tahu ke MIDTRANS Order ID nya yg kita kirim di $params tadi
        $snapToken = Snap::getSnapToken($params);

        // Simpan snap token ke database
        $pesanan->update(['snap_token' => $snapToken]);

        // Kirim snap_token ke JavaScript di browser
        return response()->json([
            'status'     => 'success',
            'code'       => 200,
            'snap_token' => $snapToken,
            'idpesanan'  => $pesanan->idpesanan,
        ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => $e->getMessage(), // tampilkan pesan error aslinya
            ], 500);
        }
    }


    public function status($idpesanan)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($idpesanan);

        // Generate QR Code berisi idpesanan
        $qrCode = new QrCode(
            data: (string) $pesanan->idpesanan,
            encoding: new Encoding('UTF-8'),
        );
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Ubah ke base64 agar bisa langsung ditampilkan di view
        $qrBase64 = base64_encode($result->getString());

        return view('customer.status', compact('pesanan', 'qrBase64'));
    }

public function dataCustomer()
{
    $customers = Customer::all();

    // Ambil nama wilayah berdasarkan ID yang tersimpan
    $customers = $customers->map(function($c) {
        $c->nama_kota      = $c->kota      ? \App\Models\Kota::find($c->kota)?->name      : '-';
        $c->nama_kecamatan = $c->kecamatan ? \App\Models\Kecamatan::find($c->kecamatan)?->name : '-';
        $c->nama_kelurahan = $c->kelurahan ? \App\Models\Kelurahan::find($c->kelurahan)?->name : '-';
        $c->nama_provinsi  = $c->provinsi  ? \App\Models\Provinsi::find($c->provinsi)?->name  : '-';
        return $c;
    });

    return view('customer.data', compact('customers'));
}

    // TAMBAH CUSTOMER 1 - Foto disimpan sebagai BLOB
// Tambah data provinsi ke semua form
public function tambahCustomer1()
{
    $provinsi = \App\Models\Provinsi::orderBy('name')->get();
    return view('customer.tambah1', compact('provinsi'));
}

public function storCustomer1(Request $request)
{
    $request->validate([
        'nama'     => 'required|string|max:255',
        'foto_blob'=> 'required|string',
    ]);

    Customer::create([
        'nama'      => $request->nama,
        'foto_blob' => $request->foto_blob,
        'provinsi'  => $request->provinsi,
        'kota'      => $request->kota,
        'kecamatan' => $request->kecamatan,
        'kelurahan' => $request->kelurahan,
        'alamat'    => $request->alamat,
        'kode_pos'  => $request->kode_pos,
    ]);

    return redirect()->route('customer.data')
                     ->with('success', 'Customer berhasil ditambahkan!');
}

    // TAMBAH CUSTOMER 2 - Foto disimpan sebagai file
public function tambahCustomer2()
{
    $provinsi = \App\Models\Provinsi::orderBy('name')->get();
    return view('customer.tambah2', compact('provinsi'));
}

public function storCustomer2(Request $request)
{
    $request->validate([
        'nama'     => 'required|string|max:255',
        'foto_blob'=> 'required|string',
    ]);

    $base64    = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto_blob);
    $imageData = base64_decode($base64);
    $filename  = 'customer_' . time() . '.png';
    $path      = 'customers/' . $filename;
    \Storage::disk('public')->put($path, $imageData);

    Customer::create([
        'nama'      => $request->nama,
        'foto_path' => $path,
        'provinsi'  => $request->provinsi,
        'kota'      => $request->kota,
        'kecamatan' => $request->kecamatan,
        'kelurahan' => $request->kelurahan,
        'alamat'    => $request->alamat,
        'kode_pos'  => $request->kode_pos,
    ]);

    return redirect()->route('customer.data')
                     ->with('success', 'Customer berhasil ditambahkan!');
}

}