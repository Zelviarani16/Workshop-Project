@extends('layouts.app')
@section('title', 'Tambah Customer - Blob')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Tambah Customer 1</h3>
            <small class="text-muted">Foto disimpan sebagai BLOB di database</small>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-md-7">
<div class="card">
<div class="card-body">

    <form action="{{ route('customer.store1') }}" method="POST" id="formCustomer1">
    @csrf

    <div class="form-group">
        <label>Nama Customer</label>
        <input type="text" name="nama" class="form-control" required
               placeholder="Masukkan nama customer">
    </div>

    {{-- PROVINSI --}}
    <div class="form-group">
        <label>Provinsi</label>
        <select id="provinsi" name="provinsi" class="form-control">
            <option value="0">-- Pilih Provinsi --</option>
            @foreach($provinsi as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- KOTA --}}
    <div class="form-group">
        <label>Kota / Kabupaten</label>
        <select id="kota" name="kota" class="form-control" disabled>
            <option value="0">-- Pilih Kota --</option>
        </select>
    </div>

    {{-- KECAMATAN --}}
    <div class="form-group">
        <label>Kecamatan</label>
        <select id="kecamatan" name="kecamatan" class="form-control" disabled>
            <option value="0">-- Pilih Kecamatan --</option>
        </select>
    </div>

    {{-- KELURAHAN --}}
    <div class="form-group">
        <label>Kelurahan</label>
        <select id="kelurahan" name="kelurahan" class="form-control" disabled>
            <option value="0">-- Pilih Kelurahan --</option>
        </select>
    </div>

    {{-- ALAMAT --}}
    <div class="form-group">
        <label>Alamat Lengkap</label>
        <textarea name="alamat" class="form-control" rows="2"
                  placeholder="Nama jalan, nomor rumah, RT/RW..."></textarea>
    </div>

    {{-- KODE POS --}}
    <div class="form-group">
        <label>Kode Pos</label>
        <input type="text" name="kode_pos" id="kode_pos" class="form-control"
               placeholder="Masukkan kode pos" maxlength="10">
    </div>

    {{-- FOTO --}}
    <div class="form-group text-center">
        <label>Foto Customer</label><br>
        <video id="video" width="100%" style="max-width:320px; border-radius:8px;"
               autoplay playsinline></video>
        <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
        <img id="preview" src="" alt="preview"
             style="display:none; width:320px; height:240px; object-fit:cover; border-radius:8px; margin-top:8px;">
    </div>

    <div class="d-flex gap-2 justify-content-center mb-3">
        <button type="button" id="btnAmbilFoto"
                class="btn btn-gradient-warning btn-rounded btn-sm">
            <i class="mdi mdi-camera"></i> Ambil Foto
        </button>
        <button type="button" id="btnRetake"
                class="btn btn-gradient-secondary btn-rounded btn-sm"
                style="display:none;">
            <i class="mdi mdi-refresh"></i> Ulangi
        </button>
    </div>

    <input type="hidden" name="foto_blob" id="fotoBlob">

    <button type="submit" class="btn btn-gradient-primary btn-rounded w-100">
        <i class="mdi mdi-content-save"></i> Simpan
    </button>

    </form>
</div>
</div>
</div>
</div>

@endsection

@push('script')
<script>
// ============================================================
// CASCADE WILAYAH
// ============================================================
function resetDropdown(selector, placeholder) {
    $(selector).html(`<option value="0">${placeholder}</option>`).prop('disabled', true);
}

function buildOptions(placeholder, data) {
    let options = `<option value="0">${placeholder}</option>`;
    data.forEach(item => options += `<option value="${item.id}">${item.name}</option>`);
    return options;
}

$('#provinsi').on('change', function() {
    const id = $(this).val();
    resetDropdown('#kota', '-- Pilih Kota --');
    resetDropdown('#kecamatan', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    if (id === '0') return;

    $.ajax({
        url: '/wilayah/kota',
        method: 'GET',
        data: { provinsi_id: id },
        success: function(res) {
            if (res.status === 'success')
                $('#kota').html(buildOptions('-- Pilih Kota --', res.data)).prop('disabled', false);
        }
    });
});

$('#kota').on('change', function() {
    const id = $(this).val();
    resetDropdown('#kecamatan', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    if (id === '0') return;

    $.ajax({
        url: '/wilayah/kecamatan',
        method: 'GET',
        data: { kota_id: id },
        success: function(res) {
            if (res.status === 'success')
                $('#kecamatan').html(buildOptions('-- Pilih Kecamatan --', res.data)).prop('disabled', false);
        }
    });
});

$('#kecamatan').on('change', function() {
    const id = $(this).val();
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    if (id === '0') return;

    $.ajax({
        url: '/wilayah/kelurahan',
        method: 'GET',
        data: { kecamatan_id: id },
        success: function(res) {
            if (res.status === 'success')
                $('#kelurahan').html(buildOptions('-- Pilih Kelurahan --', res.data)).prop('disabled', false);
        }
    });
});

// ============================================================
// KAMERA
// ============================================================
const video    = document.getElementById('video');
const canvas   = document.getElementById('canvas');
const preview  = document.getElementById('preview');
const btnAmbil = document.getElementById('btnAmbilFoto');
const btnRetake= document.getElementById('btnRetake');
const fotoBlob = document.getElementById('fotoBlob');

// Minta izin akses kamera di browser
navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => { 
        video.srcObject = stream;  // tampilkan live feed di tag <video>
    })
    .catch(err => { alert('Tidak bisa akses kamera: ' + err.message); });

// Saat tombol Ambil Foto di klik
btnAmbil.addEventListener('click', function() {
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, 320, 240); // capture frame dr video
    const dataUrl = canvas.toDataURL('image/png'); // ubah ke base64
    fotoBlob.value = dataUrl; // masukkan ke hidden input
    preview.src = dataUrl; // tampilkan preview
    preview.style.display = 'block';
    video.style.display = 'none';
    btnAmbil.style.display = 'none';
    btnRetake.style.display = 'inline-block';
});

btnRetake.addEventListener('click', function() {
    preview.style.display = 'none';
    video.style.display = 'block';
    btnAmbil.style.display = 'inline-block';
    btnRetake.style.display = 'none';
    fotoBlob.value = '';
});

document.getElementById('formCustomer1').addEventListener('submit', function(e) {
    if (!fotoBlob.value) {
        e.preventDefault();
        alert('Ambil foto dulu sebelum simpan!');
    }
});
</script>
@endpush