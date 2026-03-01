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

    <form action="{{ route('barang.store') }}" method="POST">
        @csrf

        {{-- NAMA --}}
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

        {{-- HARGA --}}
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

        {{-- BUTTON --}}
        <button type="submit"
                class="btn btn-gradient-primary btn-rounded">
            <i class="mdi mdi-content-save"></i>
            Simpan
        </button>

        <a href="{{ route('barang.index') }}"
           class="btn btn-gradient-secondary btn-rounded">
            <i class="mdi mdi-arrow-left"></i>
            Batal
        </a>

    </form>

</div>
</div>
</div>
</div>

@endsection