@extends('layouts.guest')
@section('title', 'QR Code Pesanan')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">QR Code Pesanan</h3>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
<div class="card-body text-center">

    <p class="text-muted">Tunjukkan QR Code ini ke vendor</p>

    <img src="data:image/png;base64,{{ $qrBase64 }}"
         alt="QR Code Pesanan"
         style="width:200px; height:200px;">

    <p class="mt-3">
        <strong>Pesanan #{{ $pesanan->idpesanan }}</strong><br>
        <span class="text-muted">{{ $pesanan->nama }}</span>
    </p>

    <div class="mt-2">
        @if($pesanan->status_bayar == 1)
            <label class="badge badge-gradient-success">Lunas</label>
        @elseif($pesanan->status_bayar == 2)
            <label class="badge badge-gradient-danger">Gagal</label>
        @else
            <label class="badge badge-gradient-warning">Belum Bayar</label>
        @endif
    </div>

    <a href="{{ route('pesan.riwayat') }}"
       class="btn btn-gradient-secondary btn-rounded btn-sm mt-3">
        <i class="mdi mdi-arrow-left"></i> Kembali ke Riwayat
    </a>

</div>
</div>
</div>
</div>

@endsection