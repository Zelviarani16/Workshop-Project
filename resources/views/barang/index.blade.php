@extends('layouts.app')
@section('title', 'Daftar Barang')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
#tableBarang thead th {
    position: relative;
    padding-left: 28px !important;
    padding-right: 10px !important;
    vertical-align: middle;
}
#tableBarang thead th.sorting:after,
#tableBarang thead th.sorting_asc:after,
#tableBarang thead th.sorting_desc:after { display: none !important; }
#tableBarang thead th.sorting:before,
#tableBarang thead th.sorting_asc:before,
#tableBarang thead th.sorting_desc:before {
    left: 8px !important; right: auto !important; margin-top: -8px;
}
#tableBarang thead th:first-child:before,
#tableBarang thead th:first-child:after,
#tableBarang thead th:last-child:before,
#tableBarang thead th:last-child:after { display: none !important; }
#tableBarang th:first-child, #tableBarang td:first-child {
    width: 40px; text-align: center;
    padding-left: 10px !important; padding-right: 10px !important;
}
#tableBarang th:last-child, #tableBarang td:last-child {
    width: 150px; text-align: center;
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
            <i class="mdi mdi-plus"></i> Tambah Barang
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<form action="{{ route('barang.cetak') }}"
      method="POST"
      id="formCetak"
      target="_blank">
@csrf

<div class="d-flex align-items-center gap-3 mb-3">
    <div class="d-flex align-items-center gap-2">
        <label class="mb-0">X:</label>
        <input type="number" name="x" min="1" max="5" required
               class="form-control form-control-sm" style="width:80px;">
    </div>
    <div class="d-flex align-items-center gap-2">
        <label class="mb-0">Y:</label>
        <input type="number" name="y" min="1" max="8" required
               class="form-control form-control-sm" style="width:80px;">
    </div>
    <button type="submit" class="btn btn-gradient-primary btn-rounded btn-sm">
        <i class="mdi mdi-printer"></i> Cetak
    </button>
</div>

<div class="table-responsive">
<table class="table table-hover" id="tableBarang">
    <thead>
    <tr>
        <th class="text-center"><input type="checkbox" id="checkAll"></th>
        <th>No.</th>
        <th>Nama</th>
        <th>Harga</th>
        <th class="text-center">Aksi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
    <tr>
        <td><input type="checkbox" name="selected_barang[]"
                   value="{{ $item->id_barang }}"></td>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->nama }}</td>
        <td data-order="{{ $item->harga }}">Rp {{ number_format($item->harga) }}</td>
        <td class="text-center">
            <a href="{{ route('barang.edit', $item->id_barang) }}"
               class="btn btn-gradient-info btn-sm btn-rounded">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
            <button type="button"
                    class="btn btn-gradient-danger btn-sm btn-rounded btn-hapus"
                    data-url="{{ route('barang.destroy', $item->id_barang) }}">
                <i class="mdi mdi-delete"></i> Hapus
            </button>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>

</form>

<form id="formHapus" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
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

    var table = $('#tableBarang').DataTable({ // aktifkan datatables, otomatis muncul fitur search, show entries, sorting, pagination
        columnDefs: [
            { targets: [0, 4], orderable: false, searchable: false } // index 0 = checkbox dan index 4 = aksi tidak bisa di sort
        ],
        order: [],
    });

    // Simpan checkbox yang dicentang saat pindah halaman
    var selectedIds = []; // utk simpan semua id yg dipilih

    // Saat user centang/uncentang checkbox
    $(document).on('change', 'input[name="selected_barang[]"]', function() {
        var id = $(this).val();
        if ($(this).is(':checked')) {
            if (!selectedIds.includes(id)) selectedIds.push(id);
        } else {
            selectedIds = selectedIds.filter(v => v !== id);
        }
            // Setiap kali checkbox berubah, cek statusnya:
            // Kalau dicentang → tambahkan ID ke selectedIds (kalau belum ada)
            // Kalau dilepas → hapus ID dari selectedIds dengan filter()

    });

    // Saat pindah halaman, restore checkbox yang sudah dipilih
    // Saat sdh menceklis di halaman pertama dan ingin pindah ke halaman kedua checkbox tetap tersimpan begitu juga saat previous intinya menyimpan data checkbox yg sudah dicentang
    table.on('draw', function() {
        $('input[name="selected_barang[]"]').each(function() {
            if (selectedIds.includes($(this).val())) {
                $(this).prop('checked', true);
            }
        });
        // Update checkAll status
        // Utk membandingkan kalau total checkbox asli dgn yg sudah tercentang itu SAMA. maka checkbox header ikut tercentang
        var total = $('input[name="selected_barang[]"]').length;
        var checked = $('input[name="selected_barang[]"]:checked').length;
        $('#checkAll').prop('checked', total === checked && total > 0);
    });

    // Check All
    // Saat checkbox header dicentang/dilepas, semua checkbox di halaman aktif ikut berubah dan selectedIds diupdate sekalian.
    $('#checkAll').on('change', function() {
        $('input[name="selected_barang[]"]').each(function() {
            $(this).prop('checked', $('#checkAll').is(':checked'));
            var id = $(this).val();
            if ($('#checkAll').is(':checked')) {
                if (!selectedIds.includes(id)) selectedIds.push(id);
            } else {
                selectedIds = selectedIds.filter(v => v !== id);
            }
        });
    });

    // Validasi + inject hidden input sebelum submit
    $('#formCetak').on('submit', function(e) {
        if (selectedIds.length === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 barang dulu!');
            return false;
        }
        // Hapus semua sisa hidden input dari klik sebelumnya
        $(this).find('.hidden-selected').remove();
        // Inject ulang dari selectedIds. selectedIds isinya semua ID yang pernah dicentang dari semua halaman:
        selectedIds.forEach(function(id) {
            $('#formCetak').append(
                '<input type="hidden" class="hidden-selected" name="selected_barang[]" value="' + id + '">'
            );
        });
    });

    // Hapus via JS
    $(document).on('click', '.btn-hapus', function() {
        if (!confirm('Hapus barang ini?')) return;
        $('#formHapus').attr('action', $(this).data('url')).submit();
    });

});

</script>
@endpush