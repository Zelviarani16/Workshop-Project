@extends('layouts.app')
@section('title', 'Scan Barcode Tag Harga')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Scan Barcode Tag Harga</h3>
            <small class="text-muted">Arahkan kamera ke barcode pada kertas label</small>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
<div class="card-body text-center">

    {{-- Area kamera scanner --}}
    <div id="reader" style="width:100%; border-radius:8px; overflow:hidden;"></div>

    {{-- Tombol scan ulang --}}
    <button id="btnScanLagi"
            class="btn btn-gradient-primary btn-rounded mt-3"
            style="display:none;"
            onclick="scanLagi()">
        <i class="mdi mdi-qrcode-scan"></i> Scan Lagi
    </button>

</div>
</div>

{{-- Card hasil scan --}}
<div class="card mt-3" id="hasilCard" style="display:none;">
<div class="card-body">
    <h5 class="card-title text-center mb-3">
        <i class="mdi mdi-check-circle text-success"></i> Barang Ditemukan
    </h5>
    <table class="table table-bordered">
        <tr>
            <th width="40%">ID Barang</th>
            <td id="res-id"></td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td id="res-nama"></td>
        </tr>
        <tr>
            <th>Harga</th>
            <td id="res-harga"></td>
        </tr>
    </table>
</div>
</div>

{{-- Card error --}}
<div class="card mt-3 border-danger" id="errorCard" style="display:none;">
<div class="card-body text-center">
    <i class="mdi mdi-alert-circle text-danger" style="font-size:32px;"></i>
    <p class="text-danger mt-2 mb-0" id="res-error"></p>
</div>
</div>

</div>
</div>

{{-- Audio beep --}}
<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

@endsection

@push('script')
{{-- Library Html5-QrCode --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let scanner = null;
let sudahScan = false; // flag agar tidak scan berkali-kali

function startScanner() {
    sudahScan = false;
    scanner = new Html5Qrcode("reader");

    scanner.start(
        { facingMode: "environment" }, // pakai kamera belakang
        {
            fps: 10,        // frame per second
            qrbox: { width: 250, height: 100 } // area scan lebih lebar untuk barcode
        },
        function(decodedText) {
            // Callback saat berhasil baca barcode
            if (sudahScan) return; // cegah scan ganda
            sudahScan = true;

            // 1. Bunyi beep
            document.getElementById('beepSound').play();

            // 2. Stop scanner
            scanner.stop().then(function() {
                document.getElementById('btnScanLagi').style.display = 'inline-block';
            });

            // 3. Cari data barang ke server
            cariBarang(decodedText);
        },
        function(errorMessage) {
            // Abaikan error saat kamera sedang mencari barcode
        }
    );
}

function cariBarang(idBarang) {
    document.getElementById('hasilCard').style.display  = 'none';
    document.getElementById('errorCard').style.display  = 'none';

    fetch('/barang/cari/' + idBarang)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('res-id').textContent    = data.id_barang;
                document.getElementById('res-nama').textContent  = data.nama;
                document.getElementById('res-harga').textContent = 'Rp ' + data.harga;
                document.getElementById('hasilCard').style.display = 'block';
            } else {
                document.getElementById('res-error').textContent = 'Barang dengan ID ' + idBarang + ' tidak ditemukan.';
                document.getElementById('errorCard').style.display = 'block';
            }
        })
        .catch(function() {
            document.getElementById('res-error').textContent = 'Gagal menghubungi server.';
            document.getElementById('errorCard').style.display = 'block';
        });
}

function scanLagi() {
    document.getElementById('hasilCard').style.display  = 'none';
    document.getElementById('errorCard').style.display  = 'none';
    document.getElementById('btnScanLagi').style.display = 'none';
    startScanner();
}

// Mulai scanner saat halaman dibuka
startScanner();
</script>
@endpush