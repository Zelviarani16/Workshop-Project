@extends('layouts.app')
@section('title', 'Tambah Customer - File')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Tambah Customer 2</h3>
            <small class="text-muted">Foto disimpan sebagai file, path disimpan di database</small>
        </div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-md-7">
<div class="card">
<div class="card-body">

    <form action="{{ route('customer.store2') }}" method="POST" id="formCustomer2">
    @csrf

    <div class="form-group">
        <label>Nama Customer</label>
        <input type="text" name="nama" class="form-control" required
               placeholder="Masukkan nama customer">
    </div>

    <div class="form-group">
        <label>Provinsi</label>
        <select id="provinsi2" name="provinsi" class="form-control">
            <option value="0">-- Pilih Provinsi --</option>
            @foreach($provinsi as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Kota / Kabupaten</label>
        <select id="kota2" name="kota" class="form-control" disabled>
            <option value="0">-- Pilih Kota --</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kecamatan</label>
        <select id="kecamatan2" name="kecamatan" class="form-control" disabled>
            <option value="0">-- Pilih Kecamatan --</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kelurahan</label>
        <select id="kelurahan2" name="kelurahan" class="form-control" disabled>
            <option value="0">-- Pilih Kelurahan --</option>
        </select>
    </div>

    <div class="form-group">
        <label>Alamat Lengkap</label>
        <textarea name="alamat" class="form-control" rows="2"
                  placeholder="Nama jalan, nomor rumah, RT/RW..."></textarea>
    </div>

    <div class="form-group">
        <label>Kode Pos</label>
        <input type="text" name="kode_pos" class="form-control"
               placeholder="Masukkan kode pos" maxlength="10">
    </div>

    <div class="form-group text-center">
        <label>Foto Customer</label><br>
        <video id="video2" width="100%" style="max-width:320px; border-radius:8px;"
               autoplay playsinline></video>
        <canvas id="canvas2" width="320" height="240" style="display:none;"></canvas>
        <img id="preview2" src="" alt="preview"
             style="display:none; width:320px; height:240px; object-fit:cover; border-radius:8px; margin-top:8px;">
    </div>

    <div class="d-flex gap-2 justify-content-center mb-3">
        <button type="button" id="btnAmbilFoto2"
                class="btn btn-gradient-warning btn-rounded btn-sm">
            <i class="mdi mdi-camera"></i> Ambil Foto
        </button>
        <button type="button" id="btnRetake2"
                class="btn btn-gradient-secondary btn-rounded btn-sm"
                style="display:none;">
            <i class="mdi mdi-refresh"></i> Ulangi
        </button>
    </div>

    <input type="hidden" name="foto_blob" id="fotoBlob2">

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
function resetDropdown(selector, placeholder) {
    $(selector).html(`<option value="0">${placeholder}</option>`).prop('disabled', true);
}

function buildOptions(placeholder, data) {
    let options = `<option value="0">${placeholder}</option>`;
    data.forEach(item => options += `<option value="${item.id}">${item.name}</option>`);
    return options;
}

$('#provinsi2').on('change', function() {
    const id = $(this).val();
    resetDropdown('#kota2', '-- Pilih Kota --');
    resetDropdown('#kecamatan2', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan2', '-- Pilih Kelurahan --');
    if (id === '0') return;
    $.ajax({
        url: '/wilayah/kota', method: 'GET', data: { provinsi_id: id },
        success: function(res) {
            if (res.status === 'success')
                $('#kota2').html(buildOptions('-- Pilih Kota --', res.data)).prop('disabled', false);
        }
    });
});

$('#kota2').on('change', function() {
    const id = $(this).val();
    resetDropdown('#kecamatan2', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan2', '-- Pilih Kelurahan --');
    if (id === '0') return;
    $.ajax({
        url: '/wilayah/kecamatan', method: 'GET', data: { kota_id: id },
        success: function(res) {
            if (res.status === 'success')
                $('#kecamatan2').html(buildOptions('-- Pilih Kecamatan --', res.data)).prop('disabled', false);
        }
    });
});

$('#kecamatan2').on('change', function() {
    const id = $(this).val();
    resetDropdown('#kelurahan2', '-- Pilih Kelurahan --');
    if (id === '0') return;
    $.ajax({
        url: '/wilayah/kelurahan', method: 'GET', data: { kecamatan_id: id },
        success: function(res) {
            if (res.status === 'success')
                $('#kelurahan2').html(buildOptions('-- Pilih Kelurahan --', res.data)).prop('disabled', false);
        }
    });
});

const video2    = document.getElementById('video2');
const canvas2   = document.getElementById('canvas2');
const preview2  = document.getElementById('preview2');
const btnAmbil2 = document.getElementById('btnAmbilFoto2');
const btnRetake2= document.getElementById('btnRetake2');
const fotoBlob2 = document.getElementById('fotoBlob2');

navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => { video2.srcObject = stream; })
    .catch(err => { alert('Tidak bisa akses kamera: ' + err.message); });

btnAmbil2.addEventListener('click', function() {
    const ctx = canvas2.getContext('2d');
    ctx.drawImage(video2, 0, 0, 320, 240);
    const dataUrl = canvas2.toDataURL('image/png');
    fotoBlob2.value = dataUrl;
    preview2.src = dataUrl;
    preview2.style.display = 'block';
    video2.style.display = 'none';
    btnAmbil2.style.display = 'none';
    btnRetake2.style.display = 'inline-block';
});

btnRetake2.addEventListener('click', function() {
    preview2.style.display = 'none';
    video2.style.display = 'block';
    btnAmbil2.style.display = 'inline-block';
    btnRetake2.style.display = 'none';
    fotoBlob2.value = '';
});

document.getElementById('formCustomer2').addEventListener('submit', function(e) {
    if (!fotoBlob2.value) {
        e.preventDefault();
        alert('Ambil foto dulu sebelum simpan!');
    }
});
</script>
@endpush