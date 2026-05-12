<?php
namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TokoController extends Controller
{
    public function index()
    {
        $tokos = Toko::all();
        return view('toko.index', compact('tokos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat'    => 'nullable|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'accuracy'  => 'nullable|numeric',
        ]);

        $barcode = 'TOKO-' . time();

        $toko = Toko::create([
            'barcode'   => $barcode,
            'nama_toko' => $request->nama_toko,
            'alamat'    => $request->alamat,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            // FIX #1: kalau accuracy kosong (input manual), default 0
            'accuracy'  => $request->accuracy ?? 0,
        ]);

        // FIX #3: return JSON supaya bisa pakai AJAX (tidak redirect)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Toko berhasil ditambahkan!',
                'toko'    => $toko,
            ]);
        }

        return redirect()->route('toko.index')
                         ->with('success', 'Toko berhasil ditambahkan!');
    }

    public function simpanTitik(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);
        $toko->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy ?? 0,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Titik toko berhasil disimpan!',
        ]);
    }

    public function cetakBarcode($id)
    {
        $toko      = Toko::findOrFail($id);
        $generator = new BarcodeGeneratorPNG();
        $png       = $generator->getBarcode(
            $toko->barcode,
            $generator::TYPE_CODE_128,
            2, 60
        );
        $barcode64 = base64_encode($png);

        $pdf = \Pdf::loadView('toko.barcode', compact('toko', 'barcode64'));
        return $pdf->stream('barcode-' . $toko->barcode . '.pdf');
    }

    public function cariBarcode($barcode)
    {
        $toko = Toko::where('barcode', $barcode)->first();

        if (!$toko) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json([
            'status'    => 'success',
            'idtoko'    => $toko->idtoko,
            'nama_toko' => $toko->nama_toko,
            'alamat'    => $toko->alamat ?? '-',
            'latitude'  => $toko->latitude,
            'longitude' => $toko->longitude,
            'accuracy'  => $toko->accuracy,
        ]);
    }

    public function kunjungan(Request $request)
    {
        $toko = Toko::findOrFail($request->idtoko);

        if (!$toko->latitude || !$toko->longitude) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Titik awal toko belum diset!'
            ]);
        }

        $jarak            = $this->haversine(
            $toko->latitude, $toko->longitude,
            $request->lat_sales, $request->lng_sales
        );
        $threshold        = 300;
        $accToko          = $toko->accuracy ?? 0;
        $accSales         = $request->acc_sales ?? 0;
        $thresholdEfektif = $threshold + $accToko + $accSales;
        $status           = $jarak <= $thresholdEfektif ? 'diterima' : 'ditolak';

        Kunjungan::create([
            'idtoko'    => $toko->idtoko,
            'lat_sales' => $request->lat_sales,
            'lng_sales' => $request->lng_sales,
            'acc_sales' => $accSales,
            'jarak'     => $jarak,
            'status'    => $status,
        ]);

        return response()->json([
            'status'            => 'success',
            'jarak'             => round($jarak, 1),
            'threshold'         => $threshold,
            'threshold_efektif' => round($thresholdEfektif, 1),
            'acc_toko'          => $accToko,
            'acc_sales'         => round($accSales, 1),
            'hasil'             => $status,
        ]);
    }

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $R    = 6371000;
        $dLat = ($lat2 - $lat1) * M_PI / 180;
        $dLng = ($lng2 - $lng1) * M_PI / 180;
        $a    = sin($dLat/2) * sin($dLat/2)
              + cos($lat1 * M_PI / 180) * cos($lat2 * M_PI / 180)
              * sin($dLng/2) * sin($dLng/2);
        $c    = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }

    public function kunjunganIndex()
    {
        return view('toko.kunjungan');
    }
}