@extends('layouts.guest')
@section('title', 'Status Pesanan')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Status Pesanan</h3>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
<div class="card-body text-center">

    @if($pesanan->status_bayar == 1)
        <i class="mdi mdi-check-circle text-success" style="font-size:64px;"></i>
        <h3 class="text-success mt-3">Pembayaran Lunas!</h3>

        {{-- QR CODE muncul hanya kalau sudah lunas --}}
        <div class="mt-3">
            <p class="text-muted">QR Code Pesanan #{{ $pesanan->idpesanan }}</p>
            <img src="data:image/png;base64,{{ $qrBase64 }}"
                 alt="QR Code Pesanan"
                 style="width:180px; height:180px;">
        </div>

    @elseif($pesanan->status_bayar == 2)
        <i class="mdi mdi-close-circle text-danger" style="font-size:64px;"></i>
        <h3 class="text-danger mt-3">Pembayaran Gagal</h3>
    @else
        <i class="mdi mdi-clock text-warning" style="font-size:64px;"></i>
        <h3 class="text-warning mt-3">Menunggu Pembayaran</h3>
    @endif

    <p class="text-muted mt-2">Pesanan atas nama: <strong>{{ $pesanan->nama }}</strong></p>
    <p class="text-muted">ID Pesanan: <strong>#{{ $pesanan->idpesanan }}</strong></p>

    <table class="table table-hover mt-4 text-left">
        <thead>
            <tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach($pesanan->details as $detail)
            <tr>
                <td>{{ $detail->menu->nama_menu ?? '-' }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        <h5>Total: <strong class="text-success">
            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
        </strong></h5>
    </div>

    <a href="{{ route('pesan.index') }}" class="btn btn-gradient-primary btn-rounded mt-3">
        <i class="mdi mdi-plus"></i> Pesan Lagi
    </a>

    <a href="{{ route('pesan.riwayat') }}" class="btn btn-gradient-info btn-rounded mt-3 mh-20">
        <i></i> Riwayat Saya
    </a>

</div>
</div>
</div>
</div>

{{-- Simpan ke localStorage saat halaman status dibuka --}}
{{-- Ini handle kasus bayar lewat simulator eksternal (onSuccess tidak terpanggil) --}}
<script>
@if($pesanan->status_bayar == 1)
    const idpesanan = {{ $pesanan->idpesanan }};
    const waktu     = new Date().toLocaleString('id-ID');

    let riwayat = JSON.parse(localStorage.getItem('riwayat_pesanan') || '[]');

    // Hindari duplikat kalau halaman ini dibuka berkali-kali
    const sudahAda = riwayat.some(item => item.idpesanan == idpesanan);
    if (!sudahAda) {
        riwayat.push({ idpesanan: idpesanan, waktu: waktu });
        localStorage.setItem('riwayat_pesanan', JSON.stringify(riwayat));
    }
@endif
</script>

@endsection

{{-- @extends('layouts.guest')
@section('title', 'Status Pesanan')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Status Pesanan</h3>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
<div class="card-body text-center">

    @if($pesanan->status_bayar == 1)
        <i class="mdi mdi-check-circle text-success" style="font-size:64px;"></i>
        <h3 class="text-success mt-3">Pembayaran Lunas!</h3>
    @elseif($pesanan->status_bayar == 2)
        <i class="mdi mdi-close-circle text-danger" style="font-size:64px;"></i>
        <h3 class="text-danger mt-3">Pembayaran Gagal</h3>
    @else
        <i class="mdi mdi-clock text-warning" style="font-size:64px;"></i>
        <h3 class="text-warning mt-3">Menunggu Pembayaran</h3>
    @endif

    <p class="text-muted mt-2">Pesanan atas nama: <strong>{{ $pesanan->nama }}</strong></p>
    <p class="text-muted">ID Pesanan: <strong>#{{ $pesanan->idpesanan }}</strong></p>

    <table class="table table-hover mt-4 text-left">
        <thead>
            <tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach($pesanan->details as $detail)
            <tr>
                <td>{{ $detail->menu->nama_menu ?? '-' }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        <h5>Total: <strong class="text-success">
            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
        </strong></h5>
    </div>

    <a href="{{ route('pesan.index') }}" class="btn btn-gradient-primary btn-rounded mt-3">
        <i class="mdi mdi-plus"></i> Pesan Lagi
    </a>

</div>
</div>
</div>
</div>

@endsection --}}