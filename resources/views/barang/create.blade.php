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
<!-- 
    {{--
        PERUBAHAN 1:
        Tambah id="formTambahBarang" dan novalidate pada tag form
        - id          : supaya bisa diakses oleh JavaScript
        - novalidate  : matikan validasi otomatis browser,
                        kita kontrol sendiri lewat checkValidity() & reportValidity()

        SEBELUM : <form action="{{ route('barang.store') }}" method="POST">
        SESUDAH  : <form action="{{ route('barang.store') }}" method="POST" id="formTambahBarang" novalidate>
    --}} -->
    <form action="{{ route('barang.store') }}" method="POST" id="formTambahBarang" novalidate>
        @csrf

        <!-- {{-- NAMA - tidak ada perubahan, required sudah ada --}} -->
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

        {{-- HARGA - tidak ada perubahan, required sudah ada --}}
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
    <!-- {{--
        PERUBAHAN 2:
        Button dipindah ke LUAR </form> dan type diubah dari "submit" menjadi "button"

        Kenapa harus keluar dari form?
        Karena kalau type="button" masih di dalam form, di beberapa browser
        form bisa tetap ter-submit. Dengan dikeluarkan, submit HANYA bisa
        terjadi kalau JavaScript yang memanggil form.submit()

        SEBELUM : <button type="submit" class="btn btn-gradient-primary btn-rounded">
        SESUDAH  : (form ditutup dulu, baru button di bawahnya)
    --}} -->
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

<!-- {{--
    PERUBAHAN 3:
    Tambah @push('script') di paling bawah (setelah @endsection)
    Sebelumnya tidak ada @push('script') sama sekali
--}} -->
@push('script')
<script>
$('#btnSimpan').on('click', function () {
    var form = document.getElementById('formTambahBarang');
    var btn  = $('#btnSimpan');

    // STEP 1 : cek semua input required sudah terisi
    //          checkValidity() → true kalau semua valid, false kalau ada yang kosong
    if (!form.checkValidity()) {

        // STEP 2 : tampilkan pesan error di input yang belum terisi
        //          reportValidity() → munculkan tooltip "Please fill out this field"
        form.reportValidity();
        return; // berhenti di sini, jangan submit
    }

    // STEP 3 : semua terisi → ubah button jadi spinner
    btn.prop('disabled', true); // cegah double click
    btn.html('<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Menyimpan...');

    // STEP 4 : submit form via JavaScript → data tetap tersimpan ke database
    form.submit();
});
</script>
@endpush