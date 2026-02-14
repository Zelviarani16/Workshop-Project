@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center w-100">

        <div>
            <h3 class="page-title mb-0">Daftar Buku</h3>
            <small class="text-muted">Manajemen koleksi buku perpustakaan</small>
        </div>

        <a href="{{ route('buku.create') }}"
           class="btn btn-gradient-primary btn-rounded">

            <i class="mdi mdi-plus"></i>
            Tambah Buku

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
                    <th>Kategori</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>


            <tbody>

            @forelse($bukus as $buku)

            <tr>

                <td>{{ $buku->idbuku }}</td>


                <td>

                    @php
                        $kategori = strtolower($buku->kategori->nama_kategori ?? '');
                    @endphp


                    @if($kategori == 'novel')

                        <label class="badge badge-gradient-primary">
                            Novel
                        </label>

                    @elseif($kategori == 'biografi')

                        <label class="badge badge-gradient-success">
                            Biografi
                        </label>

                    @elseif($kategori == 'komik')

                        <label class="badge badge-gradient-warning">
                            Komik
                        </label>

                    @else

                        <label class="badge badge-gradient-secondary">
                            -
                        </label>

                    @endif


                </td>


                <td>
                    <strong>{{ $buku->kode }}</strong>
                </td>


                <td>{{ $buku->judul }}</td>


                <td>{{ $buku->pengarang }}</td>


                <td class="text-center">


                    {{-- EDIT --}}
                    <a href="{{ route('buku.edit', $buku->idbuku) }}"
                       class="btn btn-gradient-info btn-sm btn-rounded">

                        <i class="mdi mdi-pencil"></i>
                        Edit

                    </a>



                    {{-- HAPUS --}}
                    <form action="{{ route('buku.destroy', $buku->idbuku) }}"
                          method="POST"
                          class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-gradient-danger btn-sm btn-rounded"
                                onclick="return confirm('Hapus buku ini?')">

                            <i class="mdi mdi-delete btn-icon-prepend"></i>
                            Hapus

                        </button>

                    </form>


                </td>


            </tr>

            @empty

            <tr>
                <td colspan="6" class="text-center text-muted">
                    Belum ada data buku
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
