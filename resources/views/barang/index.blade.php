@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Daftar Barang</h3>
            <small class="text-muted">Manajemen data barang</small>
        </div>
        <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary btn-rounded">
            <i class="mdi mdi-plus"></i>
            Tambah Barang
        </a>
    </div>
</div>

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <form action="{{ route('barang.cetak') }}" method="POST">
    @csrf

    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">X:</label>
            <input type="number" name="x" min="1" max="5" required
                   class="form-control form-control-sm" style="width: 80px;">
        </div>
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">Y:</label>
            <input type="number" name="y" min="1" max="8" required
                   class="form-control form-control-sm" style="width: 80px;">
        </div>
        <button type="submit" class="btn btn-gradient-primary btn-rounded btn-sm">
            <i class="mdi mdi-printer"></i>
            Cetak
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="tableBarang">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_barang[]" value="{{ $item->id_barang }}">
                </td>
                <td>{{ $item->id_barang }}</td>
                <td>{{ $item->nama }}</td>
                <td>Rp {{ number_format($item->harga) }}</td>
                <td class="text-center">

                    {{-- EDIT --}}
                    <a href="{{ route('barang.edit', $item->id_barang) }}"
                       class="btn btn-gradient-info btn-sm btn-rounded">
                        <i class="mdi mdi-pencil"></i>
                        Edit
                    </a>

                    {{-- HAPUS --}}
                    <form action="{{ route('barang.destroy', $item->id_barang) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-gradient-danger btn-sm btn-rounded"
                                onclick="return confirm('Hapus barang ini?')">
                            <i class="mdi mdi-delete"></i>
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    </form>

</div>
</div>
</div>
</div>

@endsection