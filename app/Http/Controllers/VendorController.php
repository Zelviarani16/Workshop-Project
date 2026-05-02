<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    // -------------------------------------------------------
    // Helper: ambil vendor yang login
    // -------------------------------------------------------
    private function getVendor()
    {
        $userId = Auth::id();
        return Vendor::where('user_id', $userId)->firstOrFail();
    }

    //     private function getVendor()
    // {
    //     return Vendor::where('user_id', auth()->id())->firstOrFail();
    // }

    // -------------------------------------------------------
    // Halaman kelola menu
    // -------------------------------------------------------
    public function menu()
    {
        $vendor = $this->getVendor();
        $menus  = Menu::where('idvendor', $vendor->idvendor)
                      ->orderBy('nama_menu')
                      ->get();
        return view('vendor.menu', compact('vendor', 'menus'));
    }

    // -------------------------------------------------------
    // Tambah menu baru
    // -------------------------------------------------------
    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|integer|min:1',
            'gambar'    => 'nullable|image|max:2048',
        ]);

        $vendor    = $this->getVendor();
        $pathGambar = null;

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $pathGambar = $request->file('gambar')
                                  ->store('menu', 'public');
        }

        Menu::create([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'path_gambar' => $pathGambar,
            'idvendor'    => $vendor->idvendor,
        ]);

        return redirect()->route('vendor.menu')
                         ->with('success', 'Menu berhasil ditambahkan!');
    }

    // -------------------------------------------------------
    // Hapus menu
    // -------------------------------------------------------
    public function destroyMenu($idmenu)
    {
        $vendor = $this->getVendor();
        $menu   = Menu::where('idmenu', $idmenu)
                      ->where('idvendor', $vendor->idvendor)
                      ->firstOrFail();
        $menu->delete();

        return redirect()->route('vendor.menu')
                         ->with('success', 'Menu berhasil dihapus!');
    }

    // -------------------------------------------------------
    // Lihat pesanan yang sudah lunas
    // -------------------------------------------------------
    public function pesanan()
    {
        $vendor = $this->getVendor();

        // Ambil pesanan yang mengandung menu dari vendor ini
        // dan sudah berstatus lunas (status_bayar = 1)
        $pesanans = Pesanan::whereHas('details.menu', function($query) use ($vendor) {
            $query->where('idvendor', $vendor->idvendor);
        })
        ->where('status_bayar', 1)
        ->with(['details.menu'])
        ->orderByDesc('idpesanan')
        ->get();

        return view('vendor.pesanan', compact('vendor', 'pesanans'));
    }

// Tampilkan halaman scan QR Code
public function scanQr()
{
    return view('vendor.scan-qr');
}

// AJAX: cari pesanan berdasarkan idpesanan hasil scan QR
public function cariPesanan($idpesanan)
{
    $pesanan = Pesanan::with('details.menu')
                      ->where('idpesanan', $idpesanan)
                      ->first();

    if (!$pesanan) {
        return response()->json([
            'status'  => 'not_found',
            'message' => 'Pesanan tidak ditemukan'
        ]);
    }

    $vendor   = $this->getVendor();
    $idvendor = $vendor->idvendor;

    // Ambil hanya detail pesanan milik vendor ini

   
    $details  = $pesanan->details->filter(function($d) use ($idvendor) {
        return $d->menu && $d->menu->idvendor == $idvendor;
    })->values();

    return response()->json([
        'status'      => 'success',
        'idpesanan'   => $pesanan->idpesanan,
        'nama'        => $pesanan->nama,
        'status_bayar'=> $pesanan->status_bayar,
        'label_bayar' => $pesanan->status_bayar == 1 ? 'Lunas' : ($pesanan->status_bayar == 2 ? 'Gagal' : 'Belum Bayar'),
        'details'     => $details->map(function($d) {
            return [
                'nama_menu' => $d->menu->nama_menu ?? '-',
                'jumlah'    => $d->jumlah,
                'subtotal'  => number_format($d->subtotal, 0, ',', '.'),
            ];
        }),
        'total' => number_format($pesanan->total, 0, ',', '.'),
    ]);
}

    
}