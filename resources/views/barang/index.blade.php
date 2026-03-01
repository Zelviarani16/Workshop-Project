@extends('layouts.app')

@section('title', 'Daftar Barang')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<style>
/* #tableBarang thead th.sorting:before,
#tableBarang thead th.sorting_asc:before,
#tableBarang thead th.sorting_desc:before {
    content: "↑";
    display: inline-block !important;
    left: 8px !important;
    right: auto !important;
    margin-top: -8px;
} */

/* HEADER TABLE */
#tableBarang thead th {
    position: relative;
    padding-left: 28px !important;
    padding-right: 10px !important;
    vertical-align: middle;
}

/* HILANGKAN ICON DEFAULT DI KANAN */
#tableBarang thead th.sorting:after,
#tableBarang thead th.sorting_asc:after,
#tableBarang thead th.sorting_desc:after {
    display: none !important;
}

/* ATUR ICON DI KIRI */
#tableBarang thead th.sorting:before,
#tableBarang thead th.sorting_asc:before,
#tableBarang thead th.sorting_desc:before {

    left: 8px !important;
    right: auto !important;
    margin-top: -8px;
}

/* NONAKTIFKAN SORTING UNTUK CHECKBOX DAN AKSI */
#tableBarang thead th:first-child:before,
#tableBarang thead th:first-child:after,
#tableBarang thead th:last-child:before,
#tableBarang thead th:last-child:after {
    display: none !important;
}

/* RAPATKAN KOLOM CHECKBOX */
#tableBarang th:first-child,
#tableBarang td:first-child {
    width: 40px;
    text-align: center;
    padding-left: 10px !important;
    padding-right: 10px !important;
}

/* RAPATKAN KOLOM AKSI */
#tableBarang th:last-child,
#tableBarang td:last-child {
    width: 150px;
    text-align: center;
}

</style>
@endpush


@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Daftar Barang</h3>
            <small class="text-muted">Manajemen data barang</small>
        </div>
        <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary btn-rounded">
            <i class="mdi mdi-plus"></i>
            Tambah Barang
        </a>
    </div>
</div>


<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<form action="{{ route('barang.cetak') }}" method="POST">
@csrf

<div class="d-flex align-items-center gap-3 mb-3">

    <div class="d-flex align-items-center gap-2">
        <label class="mb-0">X:</label>
        <input type="number"
               name="x"
               min="1"
               max="5"
               required
               class="form-control form-control-sm"
               style="width:80px;">
    </div>

    <div class="d-flex align-items-center gap-2">
        <label class="mb-0">Y:</label>
        <input type="number"
               name="y"
               min="1"
               max="8"
               required
               class="form-control form-control-sm"
               style="width:80px;">
    </div>

    <button type="submit"
            class="btn btn-gradient-primary btn-rounded btn-sm">
        <i class="mdi mdi-printer"></i>
        Cetak
    </button>

</div>


<div class="table-responsive">

<table class="table table-hover"
       id="tableBarang">

<thead>
<tr>

<th class="text-center">
<input type="checkbox" id="checkAll">
</th>

<th>ID</th>

<th>Nama</th>

<th>Harga</th>

<th class="text-center">Aksi</th>

</tr>
</thead>


<tbody>

@foreach($data as $item)

<tr>

<td>
<input type="checkbox"
       name="selected_barang[]"
       value="{{ $item->id_barang }}">
</td>

<td>
{{ $item->id_barang }}
</td>

<td>
{{ $item->nama }}
</td>

<td data-order="{{ $item->harga }}">
Rp {{ number_format($item->harga) }}
</td>

<td class="text-center">


<a href="{{ route('barang.edit', $item->id_barang) }}"
   class="btn btn-gradient-info btn-sm btn-rounded">

<i class="mdi mdi-pencil"></i>
Edit

</a>


<form action="{{ route('barang.destroy', $item->id_barang) }}"
      method="POST"
      class="d-inline">

@csrf
@method('DELETE')

<button class="btn btn-gradient-danger btn-sm btn-rounded"
        onclick="return confirm('Hapus barang ini?')">

<i class="mdi mdi-delete"></i>
Hapus

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</form>

</div>
</div>
</div>
</div>

@endsection



@push('script')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>


<script>

$(document).ready(function() {

$('#tableBarang').DataTable({

columnDefs: [

{
targets: [0,4],
orderable: false,
searchable: false
},

{
targets: 1,
type: "num"
}

],

order: [[1,'asc']],

});



$('#checkAll').on('change', function() {

$('input[name="selected_barang[]"]').prop(
'checked',
this.checked
);

});

});

</script>

@endpush