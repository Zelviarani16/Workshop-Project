<style>
@page {
    size: A4 potrait;
    margin: 5mm;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

table {
    width: 200mm;
    border-collapse: collapse;
    table-layout: fixed;
    page-break-inside: avoid;
}

td {
    width: 38mm;
    height: 18.5mm;
    text-align: center;
    vertical-align: middle;
    font-family: Arial, sans-serif;
    padding: 0,5mm;
    padding-left: 4mm;


}

.nama {
    font-size: 8pt;
    font-weight: bold;
}

.harga {
    font-size: 7pt;
}
</style>

<table>
@php $index = 0; @endphp
@for ($row = 0; $row < 8; $row++)
<tr>
    @for ($col = 0; $col < 5; $col++)
    <td>
        @if($index >= $start && isset($data[$index - $start]))
        <div class="label-wrap">
            <div class="label-inner">
                <div class="nama">{{ $data[$index - $start]->nama }}</div>
                <div class="harga">Rp {{ number_format($data[$index - $start]->harga) }}</div>
            </div>
        </div>
        @endif
    </td>
    @php $index++; @endphp
    @endfor
</tr>
@endfor
</table>