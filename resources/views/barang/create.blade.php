@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Tambah Barang</h3>
            <small class="text-muted">Tambahkan barang baru</small>
        </div>
    </div>
</div>

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <!-- Tambah id dan novalidate -->
    <form action="{{ route('barang.store') }}" method="POST" id="formTambahBarang" novalidate>
        @csrf

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   placeholder="Masukkan nama barang"
                   value="{{ old('nama') }}"
                   required>
            @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- {{-- required --}} -->
        <div class="form-group">
            <label>Harga</label>
            <input type="number"
                   name="harga"
                   class="form-control"
                   placeholder="Masukkan harga barang"
                   value="{{ old('harga') }}"
                   min="0"
                   required>
            @error('harga')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

    </form>

    <!-- Button diluar form - diubah jd type button -->
    <button type="button" id="btnSimpan" class="btn btn-gradient-primary btn-rounded">
        <i class="mdi mdi-content-save"></i> Simpan
    </button>

    <a href="{{ route('barang.index') }}" class="btn btn-gradient-secondary btn-rounded">
        <i class="mdi mdi-arrow-left"></i> Batal
    </a>

</div>
</div>
</div>
</div>

@endsection

@push('script')
<script>
$('#btnSimpan').on('click', function () {
    var form = document.getElementById('formTambahBarang');
    var btn  = $('#btnSimpan');

    // STEP 1 : cek semua input required terisi
    if (!form.checkValidity()) {

        // STEP 2 : tampilkan pesan error di input yang belum terisi
        //          reportValidity() → munculkan tooltip "Please fill out this field"
        form.reportValidity();
        return; // berhenti di sini, jangan submit
    }

    // STEP 3 : semua terisi → ubah button jadi spinner
    btn.prop('disabled', true); 
    btn.html('<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Menyimpan...');

    // STEP 4 : submit form via JS
    form.submit();
});
</script>
@endpush