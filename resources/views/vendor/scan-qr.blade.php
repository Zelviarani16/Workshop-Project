@extends('layouts.app')
@section('title', 'Scan QR Code Pesanan')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Scan QR Code Pesanan Customer</h3>
            <small class="text-muted">Arahkan kamera ke QR Code customer</small>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-6">

<div class="card">
<div class="card-body text-center">

    {{-- Area kamera scanner --}}
    <div id="reader"
         style="width:100%;
                max-width:400px;
                margin:0 auto;
                border-radius:8px;
                overflow:hidden;">
    </div>

    <button id="btnScanLagi"
            class="btn btn-gradient-primary btn-rounded mt-3 w-100"
            style="display:none;"
            onclick="scanLagi()">
        <i class="mdi mdi-qrcode-scan"></i> Scan Lagi
    </button>

</div>
</div>

{{-- Hasil scan --}}
<div class="card mt-3" id="hasilCard" style="display:none;">
<div class="card-body">

    <h5 class="text-center mb-3">
        <i class="mdi mdi-check-circle text-success"></i> Pesanan Ditemukan
    </h5>

    <p class="mb-1"><strong>Nama:</strong> <span id="res-nama"></span></p>
    <p class="mb-3">
        <strong>Status:</strong>
        <span id="res-status" class="badge"></span>
    </p>

    <div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody id="res-detail"></tbody>
    </table>
    </div>

    <div class="d-flex justify-content-end mt-2">
        <strong>Total: <span id="res-total" class="text-success ml-1"></span></strong>
    </div>

</div>
</div>

{{-- Error --}}
<div class="card mt-3" id="errorCard" style="display:none; border: 1px solid #dc3545;">
<div class="card-body text-center">
    <i class="mdi mdi-alert-circle text-danger" style="font-size:40px;"></i>
    <p class="text-danger mt-2 mb-0" id="res-error"></p>
</div>
</div>

</div>
</div>

{{-- Audio beep --}}
<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

@endsection

@push('script')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let scanner   = null;
let sudahScan = false;

function startScanner() {
    sudahScan = false;

    // Bersihkan reader sebelumnya kalau ada
    document.getElementById('reader').innerHTML = '';

    scanner = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(function(cameras) {
        if (!cameras || cameras.length === 0) {
            alert('Tidak ada kamera yang ditemukan.');
            return;
        }

        scanner.start(
            { facingMode: "environment" }, // kamera belakang
            {
                fps: 10,
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    // Ukuran box scan 70% dari lebar layar — responsive!
                    const size = Math.min(viewfinderWidth, viewfinderHeight) * 0.7;
                    return { width: size, height: size };
                }
            },
            function(decodedText) {
                if (sudahScan) return;
                sudahScan = true;

                // 1. Beep
                document.getElementById('beepSound').play().catch(function(){});

                // 2. Stop scanner
                scanner.stop().then(function() {
                    document.getElementById('btnScanLagi').style.display = 'block';
                });

                // 3. Cari pesanan
                cariPesanan(decodedText);
            },
            function(err) { /* abaikan error scan */ }
        );

    }).catch(function(err) {
        alert('Tidak bisa akses kamera: ' + err);
    });
}

function cariPesanan(idpesanan) {
    document.getElementById('hasilCard').style.display = 'none';
    document.getElementById('errorCard').style.display = 'none';

    fetch('/vendor/scan-qr/cari/' + idpesanan)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success') {

                document.getElementById('res-nama').textContent  = data.nama;
                document.getElementById('res-total').textContent = 'Rp ' + data.total;

                // Badge status bayar
                const badge = document.getElementById('res-status');
                badge.textContent = data.label_bayar;
                badge.className   = 'badge ' + (
                    data.status_bayar == 1 ? 'badge-gradient-success' :
                    data.status_bayar == 2 ? 'badge-gradient-danger'  :
                    'badge-gradient-warning'
                );

                // Isi tabel detail menu
                const tbody = document.getElementById('res-detail');
                tbody.innerHTML = '';
                if (data.details.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada menu dari vendor ini</td></tr>';
                } else {
                    data.details.forEach(function(d) {
                        tbody.innerHTML += `
                            <tr>
                                <td>${d.nama_menu}</td>
                                <td>${d.jumlah}</td>
                                <td>Rp ${d.subtotal}</td>
                            </tr>
                        `;
                    });
                }

                document.getElementById('hasilCard').style.display = 'block';

            } else {
                document.getElementById('res-error').textContent =
                    'Pesanan #' + idpesanan + ' tidak ditemukan.';
                document.getElementById('errorCard').style.display = 'block';
            }
        })
        .catch(function() {
            document.getElementById('res-error').textContent = 'Gagal menghubungi server.';
            document.getElementById('errorCard').style.display = 'block';
        });
}

function scanLagi() {
    document.getElementById('hasilCard').style.display   = 'none';
    document.getElementById('errorCard').style.display   = 'none';
    document.getElementById('btnScanLagi').style.display = 'none';
    startScanner();
}

// Mulai scanner saat halaman siap
document.addEventListener('DOMContentLoaded', function() {
    startScanner();
});
</script>
@endpush