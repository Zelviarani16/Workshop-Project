@extends('layouts.app')
@section('title', 'Edit Customer')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-header">
    <h3 class="page-title">Edit Customer</h3>
</div>

<div class="row justify-content-center">
<div class="col-md-7">
<div class="card">
<div class="card-body">

    <form action="{{ route('customer.update', $customer->idcustomer) }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control"
               value="{{ $customer->nama }}" required>
    </div>

    <div class="form-group">
        <label>Provinsi</label>
        <select id="provinsi" name="provinsi" class="form-control">
            <option value="0">-- Pilih Provinsi --</option>
            @foreach($provinsi as $p)
                <option value="{{ $p->id }}"
                    {{ $customer->provinsi == $p->id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Kota / Kabupaten</label>
        <select id="kota" name="kota" class="form-control"
                {{ $customer->kota ? '' : 'disabled' }}>
            <option value="0">-- Pilih Kota --</option>
            @foreach($kota as $k)
                <option value="{{ $k->id }}"
                    {{ $customer->kota == $k->id ? 'selected' : '' }}>
                    {{ $k->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Kecamatan</label>
        <select id="kecamatan" name="kecamatan" class="form-control"
                {{ $customer->kecamatan ? '' : 'disabled' }}>
            <option value="0">-- Pilih Kecamatan --</option>
            @foreach($kecamatan as $kc)
                <option value="{{ $kc->id }}"
                    {{ $customer->kecamatan == $kc->id ? 'selected' : '' }}>
                    {{ $kc->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Kelurahan</label>
        <select id="kelurahan" name="kelurahan" class="form-control"
                {{ $customer->kelurahan ? '' : 'disabled' }}>
            <option value="0">-- Pilih Kelurahan --</option>
            @foreach($kelurahan as $kl)
                <option value="{{ $kl->id }}"
                    {{ $customer->kelurahan == $kl->id ? 'selected' : '' }}>
                    {{ $kl->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Alamat Lengkap</label>
        <textarea name="alamat" class="form-control" rows="2">{{ $customer->alamat }}</textarea>
    </div>

    <div class="form-group">
        <label>Kode Pos</label>
        <input type="text" name="kode_pos" class="form-control"
               value="{{ $customer->kode_pos }}" maxlength="10">
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-gradient-primary btn-rounded w-100">
            <i class="mdi mdi-content-save"></i> Update
        </button>
        <a href="{{ route('customer.data') }}"
           class="btn btn-gradient-secondary btn-rounded w-100">
            Batal
        </a>
    </div>

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

function buildOptions(placeholder, data, selected = null) {
    let options = `<option value="0">${placeholder}</option>`;
    data.forEach(item => {
        const isSelected = selected && item.id == selected ? 'selected' : '';
        options += `<option value="${item.id}" ${isSelected}>${item.name}</option>`;
    });
    return options;
}

$('#provinsi').on('change', function() {
    const id = $(this).val();
    resetDropdown('#kota', '-- Pilih Kota --');
    resetDropdown('#kecamatan', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    if (id === '0') return;
    $.ajax({
        url: '/wilayah/kota', method: 'GET', data: { provinsi_id: id },
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
        url: '/wilayah/kecamatan', method: 'GET', data: { kota_id: id },
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
        url: '/wilayah/kelurahan', method: 'GET', data: { kecamatan_id: id },
        success: function(res) {
            if (res.status === 'success')
                $('#kelurahan').html(buildOptions('-- Pilih Kelurahan --', res.data)).prop('disabled', false);
        }
    });
});
</script>
@endpush