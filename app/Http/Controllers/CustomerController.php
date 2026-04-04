<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

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

        // ID unik untuk Midtrans — harus unik setiap transaksi
        $orderId = 'KANTIN-' . time() . '-' . rand(100, 999);

        // Simpan header pesanan dulu
        $pesanan = Pesanan::create([
            'nama'              => $namaGuest,
            'timestamp'         => Carbon::now(),
            'total'             => $request->total,
            'metode_bayar'      => 'QRIS',
            'status_bayar'      => 0, // belum bayar
            'midtrans_order_id' => $orderId,
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
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

        // Parameter yang dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,   // ID unik transaksi
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
        $snapToken = Snap::getSnapToken($params);

        // Simpan snap token ke database untuk referensi
        $pesanan->update(['snap_token' => $snapToken]);

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

    // -------------------------------------------------------
    // Halaman status pesanan setelah bayar
    // -------------------------------------------------------
    public function status($idpesanan)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($idpesanan);
        return view('customer.status', compact('pesanan'));

        
    }
}