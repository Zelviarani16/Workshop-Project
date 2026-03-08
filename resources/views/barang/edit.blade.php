@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Edit Barang</h3>
            <small class="text-muted">Perbarui data barang</small>
        </div>
    </div>
</div>

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    {{--
        PERUBAHAN 1:
        Tambah id="formEditBarang" dan novalidate

        SEBELUM : <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
        SESUDAH  : <form action="..." method="POST" id="formEditBarang" novalidate>
    --}}
    <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" id="formEditBarang" novalidate>
        @csrf
        @method('PUT')

        {{-- NAMA - tidak ada perubahan --}}
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   placeholder="Contoh: Pulpen"
                   value="{{ old('nama', $barang->nama) }}"
                   required>
            @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- HARGA - tidak ada perubahan --}}
        <div class="form-group">
            <label>Harga</label>
            <input type="number"
                   name="harga"
                   class="form-control"
                   placeholder="Contoh: 5000"
                   value="{{ old('harga', $barang->harga) }}"
                   min="0"
                   required>
            @error('harga')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

    </form>

    {{--
        PERUBAHAN 2:
        Button dipindah ke LUAR </form>, type diubah jadi "button"

        SEBELUM : <button type="submit" class="btn btn-gradient-primary btn-rounded">
        SESUDAH  : (di luar form, type="button", punya id="btnUpdate")
    --}}
    <button type="button" id="btnUpdate" class="btn btn-gradient-primary btn-rounded">
        <i class="mdi mdi-content-save"></i> Update
    </button>

    <a href="{{ route('barang.index') }}" class="btn btn-gradient-secondary btn-rounded">
        <i class="mdi mdi-arrow-left"></i> Batal
    </a>

</div>
</div>
</div>
</div>

@endsection

{{--
    PERUBAHAN 3:
    Tambah @push('script') di paling bawah
    Sebelumnya tidak ada sama sekali
--}}
@push('script')
<script>
$('#btnUpdate').on('click', function () {
    var form = document.getElementById('formEditBarang');
    var btn  = $('#btnUpdate');

    // STEP 1 : cek semua input required sudah terisi
    if (!form.checkValidity()) {

        // STEP 2 : tampilkan pesan error di input yang kosong
        form.reportValidity();
        return;
    }

    // STEP 3 : ubah button jadi spinner
    btn.prop('disabled', true);
    btn.html('<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Mengupdate...');

    // STEP 4 : submit form → data tetap tersimpan ke database
    form.submit();
});
</script>
@endpush