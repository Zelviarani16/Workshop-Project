@extends('layouts.guest')
@section('title', 'Riwayat Pesanan')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Riwayat Pesanan Saya</h3>
            <small class="text-muted">Pesanan tersimpan di perangkat ini</small>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
<div class="card-body">

    <div id="listRiwayat"></div>

    <div id="kosong" style="display:none;" class="text-center text-muted py-4">
        <i class="mdi mdi-history" style="font-size:48px;"></i>
        <p class="mt-2">Belum ada riwayat pesanan di perangkat ini.</p>
        <a href="{{ route('pesan.index') }}" class="btn btn-gradient-primary btn-rounded btn-sm">
            Pesan Sekarang
        </a>
    </div>

</div>
</div>
</div>
</div>

@endsection

@section('scripts')
<script>
const riwayat = JSON.parse(localStorage.getItem('riwayat_pesanan') || '[]');
const list    = document.getElementById('listRiwayat');
const kosong  = document.getElementById('kosong');

if (riwayat.length === 0) {
    kosong.style.display = 'block';
} else {
    // Tampilkan dari yang terbaru
    [...riwayat].reverse().forEach(function(item) {
        list.innerHTML += `
            <div class="d-flex justify-content-between align-items-center
                         border-bottom py-2">
                <div>
                    <strong>Pesanan #${item.idpesanan}</strong><br>
                    <small class="text-muted">${item.waktu}</small>
                </div>
                <a href="/pesan/qrcode/${item.idpesanan}"
                   class="btn btn-gradient-primary btn-rounded btn-sm">
                    <i class="mdi mdi-qrcode"></i> Lihat QR
                </a>
            </div>
        `;
    });
}
</script>
@endsection