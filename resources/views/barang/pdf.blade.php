<table width="100%">
@php $index = 0; @endphp

@for ($row = 0; $row < 8; $row++)
<tr>
    @for ($col = 0; $col < 5; $col++)
    <td style="width:20%; height:90px; border:1px solid #000; text-align:center;">
        @if($index >= $start && isset($data[$index - $start]))
            <b>{{ $data[$index - $start]->nama }}</b><br>
            Rp {{ number_format($data[$index - $start]->harga) }}
        @endif
    </td>
    @php $index++; @endphp
    @endfor
</tr>
@endfor
</table>