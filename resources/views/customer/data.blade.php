@extends('layouts.app')
@section('title', 'Data Customer')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Data Customer</h3>
            <small class="text-muted">Daftar semua customer</small>
        </div>
        <div>
            <a href="{{ route('customer.tambah1') }}" class="btn btn-gradient-primary btn-rounded btn-sm">
                <i class="mdi mdi-camera"></i> Tambah (Blob)
            </a>
            <a href="{{ route('customer.tambah2') }}" class="btn btn-gradient-info btn-rounded btn-sm">
                <i class="mdi mdi-camera"></i> Tambah (File)
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Provinsi</th>
            <th>Kota</th>
            <th>Kecamatan</th>
            <th>Kelurahan</th>
            <th>Kode Pos</th>
            <th>Foto</th>
            <th>Tipe</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
    @forelse($customers as $c)
    <tr>
        <td>{{ $c->idcustomer }}</td>
        <td>{{ $c->nama }}</td>
        <td>{{ $c->alamat ?? '-' }}</td>
<td>{{ $c->nama_provinsi ?? '-' }}</td>
<td>{{ $c->nama_kota ?? '-' }}</td>
<td>{{ $c->nama_kecamatan ?? '-' }}</td>
<td>{{ $c->nama_kelurahan ?? '-' }}</td>
        <td>{{ $c->kode_pos ?? '-' }}</td>
        <td>
            @if($c->foto_blob)
                <img src="{{ $c->foto_blob }}" width="60" height="60"
                     style="object-fit:cover; border-radius:4px;">
            @elseif($c->foto_path)
                <img src="{{ asset('storage/' . $c->foto_path) }}" width="60" height="60"
                     style="object-fit:cover; border-radius:4px;">
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td>
            @if($c->foto_blob)
                <label class="badge badge-gradient-warning">BLOB</label>
            @else
                <label class="badge badge-gradient-info">File</label>
            @endif
        </td>
        <td class="text-center">
            <a href="{{ route('customer.edit', $c->idcustomer) }}"
               class="btn btn-gradient-warning btn-sm btn-rounded">
                <i class="mdi mdi-pencil"></i>
            </a>
            <form action="{{ route('customer.destroy', $c->idcustomer) }}"
                  method="POST" style="display:inline;"
                  onsubmit="return confirm('Yakin hapus customer ini?')">
                @csrf
                <button type="submit" class="btn btn-gradient-danger btn-sm btn-rounded">
                    <i class="mdi mdi-delete"></i>
                </button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="10" class="text-center text-muted">Belum ada data customer</td>
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