@extends('layouts.app')
@section('title', 'Titik Kunjungan Sales')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Titik Kunjungan Sales</h3>
            <small class="text-muted">Scan barcode toko untuk verifikasi kunjungan</small>
        </div>
    </div>
</div>

<div class="row">

{{-- Kolom kiri: scan barcode + info toko --}}
<div class="col-12 col-md-6 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <h5 class="card-title">
        <i class="mdi mdi-barcode-scan"></i> Scan Barcode Toko
    </h5>
    <p class="text-muted small">Arahkan kamera ke barcode yang tertempel di toko.</p>

    {{-- Area kamera --}}
    <div id="reader"
         style="width:100%; max-width:400px; margin:0 auto;
                border-radius:8px; overflow:hidden;"></div>

    <button id="btnScanLagi"
            class="btn btn-gradient-secondary btn-rounded btn-sm mt-2 w-100"
            style="display:none;"
            onclick="scanLagi()">
        <i class="mdi mdi-refresh"></i> Scan Lagi
    </button>

    {{-- Info toko hasil scan --}}
    <div id="infoToko" class="mt-3" style="display:none;">
        <hr>
        <h6 class="fw-bold">Info Toko</h6>
        <table class="table table-sm table-bordered mb-0">
            <tr><th width="40%">Nama Toko</th><td id="info-nama"></td></tr>
            <tr><th>Alamat</th><td id="info-alamat"></td></tr>
            <tr><th>Latitude</th><td id="info-lat"></td></tr>
            <tr><th>Longitude</th><td id="info-lng"></td></tr>
            <tr><th>Accuracy Toko</th><td id="info-acc"></td></tr>
        </table>
    </div>

</div>
</div>
</div>

{{-- Kolom kanan: posisi sales + hasil --}}
<div class="col-12 col-md-6 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <h5 class="card-title">
        <i class="mdi mdi-crosshairs-gps"></i> Posisi Sales
    </h5>
    <p class="text-muted small">
        Scan barcode toko dulu, lalu ambil posisi kamu sekarang.
    </p>

    <button id="btnAmbilPosisi"
            class="btn btn-gradient-success btn-rounded w-100"
            disabled
            onclick="ambilPosisiSales()">
        <i class="mdi mdi-map-marker-radius"></i> Ambil Posisi Saya
    </button>

    <div id="infoPosisi" class="mt-3" style="display:none;">
        <table class="table table-sm table-bordered">
            <tr><th width="40%">Latitude</th><td id="pos-lat"></td></tr>
            <tr><th>Longitude</th><td id="pos-lng"></td></tr>
            <tr><th>Accuracy</th><td id="pos-acc"></td></tr>
        </table>

        <button type="button" class="btn btn-gradient-primary btn-rounded w-100"
                onclick="kirimKunjungan()">
            <i class="mdi mdi-send"></i> Kirim Kunjungan
        </button>
    </div>

    {{-- Hasil kunjungan --}}
    <div id="hasilKunjungan" class="mt-3" style="display:none;"></div>

</div>
</div>
</div>

</div>

<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

@endsection

@push('script')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>

let scanner      = null;
let sudahScan    = false;
let tokoTerpilih = null;
let posisiSales  = null;

// ── GEOLOCATION AKURAT ─────────────────────────────────────
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult  = null;
        const startTime = Date.now();
        const watchId   = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;
                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }
                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }
                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout, tidak dapat posisi'));
                }
            },
            (err) => reject(err),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

// ── SCANNER ────────────────────────────────────────────────
function startScanner() {
    sudahScan = false;
    document.getElementById('reader').innerHTML = '';
    scanner = new Html5Qrcode('reader');

    Html5Qrcode.getCameras().then(function(cameras) {
        if (!cameras || cameras.length === 0) {
            alert('Tidak ada kamera ditemukan.');
            return;
        }

        // Tampilkan semua kamera yang terdeteksi di console
        // biar kamu tahu index mana yang laptop
        console.log('Kamera tersedia:', cameras);

        // Pakai kamera index 0 (biasanya webcam laptop)
        // Kalau mau paksa HP, ganti index ke 1
        scanner.start(
            { deviceId: { exact: cameras[0].id } },
            {
                fps: 10,
                qrbox: { width: 300, height: 150 }
            },
            function(decodedText) {
                if (sudahScan) return;
                sudahScan = true;
                document.getElementById('beepSound').play().catch(()=>{});
                scanner.stop().then(function() {
                    document.getElementById('btnScanLagi').style.display = 'block';
                });
                cariToko(decodedText);
            },
            function(err) {}
        );
    }).catch(function(err) {
        alert('Tidak bisa akses kamera: ' + err);
    });
}

function scanLagi() {
    tokoTerpilih = null;
    posisiSales  = null;
    document.getElementById('infoToko').style.display        = 'none';
    document.getElementById('infoPosisi').style.display      = 'none';
    document.getElementById('hasilKunjungan').style.display  = 'none';
    document.getElementById('btnScanLagi').style.display     = 'none';
    document.getElementById('btnAmbilPosisi').disabled       = true;
    startScanner();
}

// ── CARI TOKO ──────────────────────────────────────────────
function cariToko(barcode) {
    fetch('/toko/cari-barcode/' + encodeURIComponent(barcode))
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                tokoTerpilih = data;
                document.getElementById('info-nama').textContent   = data.nama_toko;
                document.getElementById('info-alamat').textContent = data.alamat ?? '-';
                document.getElementById('info-lat').textContent    = data.latitude ?? '-';
                document.getElementById('info-lng').textContent    = data.longitude ?? '-';
                document.getElementById('info-acc').textContent    = data.accuracy
                    ? data.accuracy + ' m' : '-';
                document.getElementById('infoToko').style.display  = 'block';

                if (data.latitude && data.longitude) {
                    // Aktifkan tombol ambil posisi setelah toko ditemukan
                    document.getElementById('btnAmbilPosisi').disabled = false;
                } else {
                    alert('Titik awal toko belum diset! Hubungi admin.');
                }
            } else {
                alert('Toko dengan barcode "' + barcode + '" tidak ditemukan!');
            }
        })
        .catch(() => alert('Gagal menghubungi server.'));
}

// ── AMBIL POSISI SALES ─────────────────────────────────────
async function ambilPosisiSales() {
    const btn = document.getElementById('btnAmbilPosisi');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengambil posisi...';

    try {
        const pos = await getAccuratePosition(200, 10000);
        posisiSales = {
            lat: pos.coords.latitude,
            lng: pos.coords.longitude,
            acc: pos.coords.accuracy
        };
        document.getElementById('pos-lat').textContent      = posisiSales.lat.toFixed(7);
        document.getElementById('pos-lng').textContent      = posisiSales.lng.toFixed(7);
        document.getElementById('pos-acc').textContent      = posisiSales.acc.toFixed(1) + ' m';
        document.getElementById('infoPosisi').style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i class="mdi mdi-map-marker-radius"></i> Ambil Posisi Lagi';
    } catch(err) {
        alert('Gagal ambil posisi: ' + err.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="mdi mdi-map-marker-radius"></i> Ambil Posisi Saya';
    }
}

// ── KIRIM KUNJUNGAN ────────────────────────────────────────
function kirimKunjungan() {

    if (!tokoTerpilih || !posisiSales) {
        alert('Data toko atau posisi sales belum ada!');
        return;
    }

    fetch('/toko/kunjungan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            idtoko    : tokoTerpilih.idtoko,
            lat_sales : posisiSales.lat,
            lng_sales : posisiSales.lng,
            acc_sales : posisiSales.acc,
        })
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === 'success') {

            const ok = data.hasil === 'diterima';

            document.getElementById('hasilKunjungan').innerHTML = `
                <div class="alert ${ok ? 'alert-success' : 'alert-danger'}">

                    <h5 class="mb-2">
                        ${ok ? '✅ Kunjungan DITERIMA' : '❌ Kunjungan DITOLAK'}
                    </h5>

                    <hr>

                    <p class="mb-1">
                        <strong>Jarak aktual:</strong> ${data.jarak} m
                    </p>

                    <p class="mb-1">
                        <strong>Threshold:</strong> ${data.threshold} m
                    </p>

                    <p class="mb-1">
                        <strong>Accuracy toko:</strong> ${data.acc_toko} m
                    </p>

                    <p class="mb-1">
                        <strong>Accuracy sales:</strong> ${data.acc_sales} m
                    </p>

                    <p class="mb-0">
                        <strong>Threshold efektif:</strong>
                        ${data.threshold_efektif} m

                        <br>

                        <small class="text-muted">
                            (${data.threshold} + ${data.acc_toko} + ${data.acc_sales})
                        </small>
                    </p>

                </div>
            `;

            document.getElementById('hasilKunjungan').style.display = 'block';

        } else {
            alert(data.message);
        }
    })
    .catch((err) => {
        console.error(err);
        alert('Gagal mengirim kunjungan.');
    });
}

// ── START SAAT DOM SIAP ────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    startScanner();
});
</script>
@endpush