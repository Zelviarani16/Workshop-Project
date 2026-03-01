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


    <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
        @csrf
        @method('PUT')


        {{-- ID BARANG --}}
        <div class="form-group">

            <label>ID Barang</label>

            <input type="text"
                   class="form-control"
                   value="{{ $barang->id_barang }}"
                   disabled>

            <small class="text-muted">ID barang tidak dapat diubah</small>

        </div>


        {{-- NAMA --}}
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


        {{-- HARGA --}}
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


        {{-- BUTTON --}}
        <button type="submit"
                class="btn btn-gradient-primary btn-rounded">

            <i class="mdi mdi-content-save"></i>
            Update

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