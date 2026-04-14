<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;


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

    $generator = new BarcodeGeneratorPNG();
    $barcodes = [];
    foreach ($data as $item) {
        $png = $generator->getBarcode(
            $item->id_barang,
            $generator::TYPE_CODE_128,
            1,
            20
        );
        $barcodes[$item->id_barang] = 'data:image/png;base64,' . base64_encode($png);
    }

    $pdf = Pdf::loadView('barang.pdf', compact('data', 'start', 'barcodes'));
    return $pdf->stream('tag-harga.pdf');
}
}