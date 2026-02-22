<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class UndanganController extends Controller
{
     public function generate()
    {
        $data = [
            'tanggal' => '15 September 2025',
            'nomor' => '279/UN.3.FV/I/TEKNOVISTAFEST/HIMTI/IX/2025',
            'lampiran' => '-',
            'tujuan' => 'Ketua Himpunan Mahasiswa D4 Teknik Informatika',
            'nama_kegiatan' => 'TEKNOVISTAFEST 2025',
            'hari_tanggal' => 'Minggu, 21 September 2025',
            'waktu' => '08.00 - 17.00 WIB',
            'tempat' => 'Malleo Hall, UNAIR',
            'nama_tamu' => 'Fitria'
        ];

        $pdf = Pdf::loadView('undangan', $data);

        return $pdf->stream('undangan.pdf');
    }
}
