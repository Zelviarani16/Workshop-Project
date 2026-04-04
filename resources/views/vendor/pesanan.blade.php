@extends('layouts.app')
@section('title', 'Pesanan Lunas')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Pesanan Lunas</h3>
            <small class="text-muted">{{ $vendor->nama_vendor }}</small>
        </div>
        <a href="{{ route('vendor.menu') }}" class="btn btn-gradient-warning btn-rounded">
            <i class="mdi mdi-food"></i> Kelola Menu
        </a>
    </div>
</div>

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pemesan</th>
                <th>Waktu</th>
                <th>Detail Menu</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $pesanan)
            <tr>
                <td>{{ $pesanan->idpesanan }}</td>
                <td><strong>{{ $pesanan->nama }}</strong></td>
                <td>{{ $pesanan->timestamp }}</td>
                <td>
                    @foreach($pesanan->details as $detail)
                        <small>
                            {{ $detail->menu->nama_menu ?? '-' }}
                            x{{ $detail->jumlah }}
                            @if($detail->catatan)
                                <em class="text-muted">({{ $detail->catatan }})</em>
                            @endif
                        </small><br>
                    @endforeach
                </td>
                <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                <td>
                    <label class="badge badge-gradient-success">Lunas</label>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Belum ada pesanan yang lunas
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