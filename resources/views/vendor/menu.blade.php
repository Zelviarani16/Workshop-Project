@extends('layouts.app')
@section('title', 'Kelola Menu')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Kelola Menu</h3>
            <small class="text-muted">{{ $vendor->nama_vendor }}</small>
        </div>
        <a href="{{ route('vendor.pesanan') }}" class="btn btn-gradient-info btn-rounded">
            <i class="mdi mdi-clipboard-list"></i> Lihat Pesanan Lunas
        </a>
    </div>
</div>

<div class="row">
<div class="col-md-4 grid-margin stretch-card">
<div class="card">
<div class="card-body">
    <h4 class="card-title">Tambah Menu Baru</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('vendor.menu.store') }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label>Foto Menu <small class="text-muted">(opsional)</small></label>
            <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-gradient-primary btn-rounded w-100">
            <i class="mdi mdi-plus"></i> Tambah Menu
        </button>
    </form>

</div>
</div>
</div>

<div class="col-md-8 grid-margin stretch-card">
<div class="card">
<div class="card-body">
    <h4 class="card-title">Daftar Menu</h4>

    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr><th>Foto</th><th>Nama Menu</th><th>Harga</th><th class="text-center">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr>
                <td>
                    @if($menu->path_gambar)
                        <img src="{{ asset('storage/' . $menu->path_gambar) }}"
                             width="50" height="50"
                             style="object-fit:cover; border-radius:6px;">
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ $menu->nama_menu }}</td>
                <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                <td class="text-center">
                    <form action="{{ route('vendor.menu.destroy', $menu->idmenu) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-gradient-danger btn-sm btn-rounded"
                                onclick="return confirm('Hapus menu ini?')">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Belum ada menu</td>
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