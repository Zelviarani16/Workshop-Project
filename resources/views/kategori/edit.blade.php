@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center w-100">

        <div>
            <h3 class="page-title mb-0">Edit Kategori</h3>
            <small class="text-muted">Perbarui data kategori buku perpustakaan</small>
        </div>

    </div>

</div>



<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">

<div class="card-body">


    <form action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
        @csrf
        @method('PUT')


        {{-- NAMA KATEGORI --}}
        <div class="form-group">

            <label><strong>Nama Kategori</strong></label>

            <input type="text"
                   name="nama_kategori"
                   id="nama_kategori"
                   class="form-control"
                   placeholder="Masukkan nama kategori"
                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                   required>

            @error('nama_kategori')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>



        {{-- BUTTON --}}
        <button type="submit"
                class="btn btn-gradient-primary btn-rounded">

            <i class="mdi mdi-content-save"></i>
            Update

        </button>


        <a href="{{ route('kategori.index') }}"
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
