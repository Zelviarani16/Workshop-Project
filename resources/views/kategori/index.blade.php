@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center w-100">

        <div>
            <h3 class="page-title mb-0">Daftar Kategori</h3>
            <small class="text-muted">Manajemen kategori buku perpustakaan</small>
        </div>

        <a href="{{ route('kategori.create') }}"
           class="btn btn-gradient-primary btn-rounded">

            <i class="mdi mdi-plus"></i>
            Tambah Kategori

        </a>

    </div>

</div>



<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">

<div class="card-body">


    {{-- alert --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif



    <div class="table-responsive">

        <table class="table table-hover">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>


            <tbody>

            @forelse($kategoris as $kategori)

            <tr>

                <td>{{ $kategori->idkategori }}</td>


                <td>
                    {{ $kategori->nama_kategori }}
                </td>


                <td class="text-center">


                    {{-- EDIT --}}
                    <a href="{{ route('kategori.edit', $kategori->idkategori) }}"
                       class="btn btn-gradient-info btn-sm btn-rounded">

                        <i class="mdi mdi-pencil"></i>
                        Edit

                    </a>



                    {{-- HAPUS --}}
                    <form action="{{ route('kategori.destroy', $kategori->idkategori) }}"
                          method="POST"
                          class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-gradient-danger btn-sm btn-rounded"
                                onclick="return confirm('Hapus kategori ini?')">

                            <i class="mdi mdi-delete"></i>
                            Hapus

                        </button>

                    </form>


                </td>

            </tr>

            @empty

            <tr>
                <td colspan="3" class="text-center text-muted">
                    Belum ada data kategori
                </td>
            </tr>

            @endforelse


            </tbody>

        </table>

    </div>


</div>
</div>
</div>
</div>


@endsection
