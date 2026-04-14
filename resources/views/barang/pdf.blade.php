<style>
@page {
    size: A4 portrait;
    margin-top: 3mm;
    margin-bottom: 3mm;
    margin-left: 4mm;
    margin-right: 4mm;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

table {
    width: 202mm;
    border-collapse: separate;
    border-spacing: 3mm 2mm;
    table-layout: fixed;
}

tr {
    height: 18mm;
}

td {
    width: 38mm;
    height: 18mm;
    text-align: center;
    vertical-align: middle;
    font-family: Arial, sans-serif;
    overflow: hidden;
    border: 0.3pt dashed #aaa;
}
</style>

<table>
@php $index = 0; @endphp
@for ($row = 0; $row < 8; $row++)
<tr>
    @for ($col = 0; $col < 5; $col++)
    <td>
        @if($index >= $start && isset($data[$index - $start]))
        @php $item = $data[$index - $start]; @endphp

        {{-- Barcode --}}
        <img src="{{ $barcodes[$item->id_barang] }}"
             style="width:30mm; height:4mm; display:block; margin:0 auto;">

        {{-- ID Barang --}}
        <div style="font-size:5pt; text-align:center; margin-top:0.3mm;">
            {{ $item->id_barang }}
        </div>

        {{-- Nama --}}
        <div style="font-size:5.5pt; font-weight:bold; text-align:center;
                    line-height:1.1; white-space:normal; margin-top:0.5mm;">
            {{ $item->nama }}
        </div>

        {{-- Harga --}}
        <div style="font-size:5pt; text-align:center; margin-top:0.3mm;">
            Rp {{ number_format($item->harga) }}
        </div>

        @endif
    </td>
    @php $index++; @endphp
    @endfor
</tr>
@endfor
</table>