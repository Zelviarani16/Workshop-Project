<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $data = Barang::all();
        return view('barang.index', compact('data'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {

    $request->validate([
        'nama'  => 'required|string|max:50',
        'harga' => 'required|integer|min:0',
    ]);

        Barang::create($request->only('nama','harga'));
        return redirect()->route('barang.index');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update($request->only('nama','harga'));
        return redirect()->route('barang.index');
    }

    public function destroy($id)
    {
        Barang::destroy($id);
        return back();
    }

    // CETAK PDF
    public function cetak(Request $request)
{
    if (!$request->selected_barang) {
        return back()->with('error', 'Pilih minimal 1 barang dulu!');
    }

    $selected = $request->selected_barang;
    $x = $request->x;
    $y = $request->y;

    $data = Barang::whereIn('id_barang', $selected)->get();
    $start = ($y - 1) * 5 + ($x - 1);

    $pdf = Pdf::loadView('barang.pdf', compact('data', 'start'));
    return $pdf->stream('tag-harga.pdf');
}
}