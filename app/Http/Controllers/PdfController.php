<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function sertifikat()
    {
        $pdf = Pdf::loadView('sertifikat') // ambil blade sertifikat
                  ->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat.pdf');
    }

    public function undangan()
    {
        $data = [
            'tanggal'       => '15 September 2025',
            'nomor'         => '279/UN.3.FV/I/TEKNOVISTAFEST/HIMTI/IX/2025',
            'lampiran'      => '-',
            'tujuan'        => 'Ketua Himpunan Mahasiswa D4 Teknik Informatika',
            'nama_kegiatan' => 'TEKNOVISTAFEST 2025',
            'hari_tanggal'  => 'Minggu, 21 September 2025',
            'waktu'         => '08.00 - 17.00 WIB',
            'tempat'        => 'Malleo Hall, UNAIR'
        ];

        $pdf = Pdf::loadView('undangan', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download('undangan.pdf');
    }
}