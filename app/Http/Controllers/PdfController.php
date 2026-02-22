<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function sertifikat()
{
    $pdf = Pdf::loadView('sertifikat')
              ->setPaper('a4', 'landscape');

    return $pdf->download('sertifikat.pdf');
}

public function pengumuman()
{
    $pdf = Pdf::loadView('pengumuman')
              ->setPaper('a4', 'portrait');

    return $pdf->download('pengumuman.pdf');
}


}
