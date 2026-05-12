@extends('layouts.app')
@section('title', 'Data Toko')

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Data Toko</h3>
            <small class="text-muted">Manajemen data toko</small>
        </div>
        <button class="btn btn-gradient-primary btn-rounded btn-sm"
                data-bs-toggle="modal" data-bs-target="#modalTambahToko">
            <i class="mdi mdi-plus"></i> Tambah Toko
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <h5 class="card-title">List Toko</h5>

    <div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead class="table-light">
        <tr>
            <th>Barcode</th>
            <th>Nama Toko</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Accuracy</th>
            <th class="text-center">Cetak Barcode</th>
        </tr>
        </thead>
        <tbody>
        @forelse($tokos as $toko)
        <tr>
            <td><small>{{ $toko->barcode }}</small></td>
            <td>{{ $toko->nama_toko }}</td>
            <td>{{ $toko->latitude ?? '-' }}</td>
            <td>{{ $toko->longitude ?? '-' }}</td>
            <td>{{ $toko->accuracy ? $toko->accuracy . ' m' : '-' }}</td>
            <td class="text-center">
                <a href="{{ route('toko.barcode', $toko->idtoko) }}"
                   target="_blank"
                   class="btn btn-gradient-info btn-sm btn-rounded">
                    <i class="mdi mdi-barcode"></i> Cetak
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">Belum ada toko</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>

</div>
</div>
</div>
</div>

{{-- MODAL TAMBAH TOKO --}}
<div class="modal fade" id="modalTambahToko" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Tambah Toko</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <form action="{{ route('toko.store') }}" method="POST" id="formTambahToko">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nama Toko</label>
        <input type="text" name="nama_toko" class="form-control" required
               placeholder="Masukkan nama toko">
    </div>

    <div class="mb-3">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="2"
                  placeholder="Alamat toko"></textarea>
    </div>

    <hr>
    <p class="fw-bold mb-2">
        <i class="mdi mdi-map-marker"></i> Titik Awal Toko
    </p>

    {{-- Input manual atau otomatis GPS --}}
    <div class="mb-2">
        <label class="form-label">Latitude</label>
        <input type="number" name="latitude" id="inputLat"
               class="form-control" step="any"
               placeholder="Contoh: -7.1234567">
    </div>
    <div class="mb-2">
        <label class="form-label">Longitude</label>
        <input type="number" name="longitude" id="inputLng"
               class="form-control" step="any"
               placeholder="Contoh: 112.1234567">
    </div>
    <div class="mb-3">
        <label class="form-label">Accuracy (meter)</label>
        <input type="number" name="accuracy" id="inputAcc"
               class="form-control" step="any"
               placeholder="Otomatis dari GPS">
    </div>

    {{-- Tombol ambil GPS otomatis --}}
    <button type="button"
            class="btn btn-gradient-warning btn-rounded w-100 mb-3"
            onclick="ambilGPS()">
        <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi via GPS
    </button>

    <div id="statusGPS" class="text-muted small mb-2" style="display:none;"></div>

    <button type="submit" class="btn btn-gradient-primary btn-rounded w-100">
        <i class="mdi mdi-content-save"></i> Simpan Toko
    </button>

    </form>
</div>
</div>
</div>
</div>

@endsection

@push('script')
<script>

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
                    else reject(new Error('Timeout'));
                }
            },
            (err) => reject(err),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

async function ambilGPS() {
    const btn    = event.currentTarget;
    const status = document.getElementById('statusGPS');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengambil lokasi...';
    status.style.display = 'block';
    status.textContent   = 'Menunggu GPS...';

    try {
        const pos = await getAccuratePosition(200, 10000);
        document.getElementById('inputLat').value = pos.coords.latitude;
        document.getElementById('inputLng').value = pos.coords.longitude;
        document.getElementById('inputAcc').value = pos.coords.accuracy.toFixed(2);
        status.textContent = '✅ Lokasi berhasil diambil! Accuracy: ' + pos.coords.accuracy.toFixed(1) + ' m';
    } catch(err) {
        status.textContent = '❌ Gagal ambil lokasi: ' + err.message;
    }

    btn.disabled = false;
    btn.innerHTML = '<i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi via GPS';
}

</script>
@endpush