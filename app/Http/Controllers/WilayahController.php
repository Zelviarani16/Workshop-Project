<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        $provinsi = Provinsi::orderBy('name')->get();
        return view('wilayah.index', compact('provinsi'));
    }

    public function getKota(Request $request)
    {
    //   where('province_id',          → filter WHERE province_id
    //   $request->provinsi_id)  → = 35 (yang dikirim dari AJAX tadi)
        $kota = Kota::where('province_id', $request->provinsi_id)
                        ->orderBy('name')
                        ->get();
        return response()->json([
            'status'    => 'success',
            'code'      => 200,
            'data'      => $kota // isinya adalah array kota
        // - return response()->json([...])
        // maka (JSON otomatis balik ke browser)
        ]);
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = Kecamatan::where('regency_id', $request->kota_id)
                        ->orderBy('name')
                        ->get();

        return response()->json([
            'status'    => 'success',
            'code'      => 200,
            'data'      => $kecamatan
        ]);
    }

        public function getKelurahan(Request $request)
    {
        $kelurahan = Kelurahan::where('district_id', $request->kecamatan_id)
                              ->orderBy('name')
                              ->get();
        return response()->json([
            'status' => 'success',
            'code'   => 200,
            'data'   => $kelurahan
        ]);
    }


}
