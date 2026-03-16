<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir.index');
    }


    // Cari barang berdasarkan kode (id_barang)
    // Dipanggil via AJAX saat kasir tekan Enter di input kode
    public function cariBarang(Request $request)
    {
        $barang = Barang::where('id_barang', $request->kode_barang)->first();

        // Kalau barang tidak ditemukan, balas dengan status not_found
        if (!$barang) {
            return response()->json([
                'status'  => 'not_found',
                'code'    => 404,
                'message' => 'Barang tidak ditemukan',
            ]);
        }

        // Kalau ditemukan, kirim data barang
        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Barang ditemukan',
            'data'    => [
                'id_barang' => $barang->id_barang,
                'nama'      => $barang->nama,
                'harga'     => $barang->harga,
            ]
        ]);
    }


    // Simpan transaksi ke database
    // Dipanggil via AJAX saat kasir klik tombol "Bayar"
    public function bayar(Request $request)
    {
        // Validasi: pastikan ada items dan total
        $request->validate([
            'items'   => 'required|array|min:1',
            'total'   => 'required|integer|min:1',
        ]);

        // Simpan header penjualan
        $penjualan = Penjualan::create([
            'timestamp' => Carbon::now(),
            'total'     => $request->total,
        ]);

        // Simpan setiap item detail
        foreach ($request->items as $item) {
            PenjualanDetail::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang'    => $item['id_barang'],
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $item['subtotal'],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Transaksi berhasil disimpan',
            'data'    => [
            'id_penjualan' => $penjualan->id_penjualan
            ]
        ]);
    }
}