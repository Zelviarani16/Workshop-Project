@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center w-100">

        <div>
            <h3 class="page-title mb-0">Tambah Buku</h3>
            <small class="text-muted">Tambahkan buku baru ke koleksi perpustakaan</small>
        </div>

    </div>

</div>



<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">

<div class="card-body">


    <form action="{{ route('buku.store') }}" method="POST">
        @csrf


        {{-- KATEGORI --}}
        <div class="form-group">

            <label>Kategori</label>

            <select name="idkategori"
                    class="form-control"
                    required>

                <option value="">-- Pilih Kategori --</option>

                @foreach($kategoris as $kategori)

                    <option value="{{ $kategori->idkategori }}"
                        {{ old('idkategori') == $kategori->idkategori ? 'selected' : '' }}>

                        {{ $kategori->nama_kategori }}

                    </option>

                @endforeach

            </select>

            @error('idkategori')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>



        {{-- KODE --}}
        <div class="form-group">

            <label>Kode Buku</label>

            <input type="text"
                   name="kode"
                   class="form-control"
                   placeholder="Masukkan kode buku"
                   value="{{ old('kode') }}"
                   required>

            @error('kode')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>



        {{-- JUDUL --}}
        <div class="form-group">

            <label>Judul Buku</label>

            <input type="text"
                   name="judul"
                   class="form-control"
                   placeholder="Masukkan judul buku"
                   value="{{ old('judul') }}"
                   required>

            @error('judul')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>



        {{-- PENGARANG --}}
        <div class="form-group">

            <label>Pengarang</label>

            <input type="text"
                   name="pengarang"
                   class="form-control"
                   placeholder="Masukkan nama pengarang"
                   value="{{ old('pengarang') }}"
                   required>

            @error('pengarang')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>



        {{-- BUTTON --}}
        <button type="submit"
                class="btn btn-gradient-primary btn-rounded">

            <i class="mdi mdi-content-save"></i>
            Simpan

        </button>


        <a href="{{ route('buku.index') }}"
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
