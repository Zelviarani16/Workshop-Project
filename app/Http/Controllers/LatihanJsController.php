<?php

namespace App\Http\Controllers;

class LatihanJsController extends Controller
{
    // Studi Kasus 2A + 3A
    public function tabelBiasa()
    {
        return view('tm4.tabel-biasa');
    }

    // Studi Kasus 2B + 3B
    public function datatables()
    {
        return view('tm4.datatables');
    }

    //  Studi Kasus 4
    public function select()
    {
        return view('tm4.select');
    }
}