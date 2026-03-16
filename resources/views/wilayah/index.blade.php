@extends('layouts.app')

@section('title', 'Data Wilayah Indonesia')

@section('content')

{{-- ================================================== --}}
{{-- PAGE HEADER --}}
{{-- ================================================== --}}
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Data Wilayah Indonesia</h3>
            <small class="text-muted">Dropdown bertingkat: Provinsi → Kota → Kecamatan → Kelurahan</small>
        </div>
    </div>
</div>

{{-- ================================================== --}}
{{-- LIBRARY YANG DIBUTUHKAN --}}
{{-- jQuery  : untuk versi AJAX jQuery --}}
{{-- Axios   : untuk versi Axios + Promise --}}
{{-- Swal2   : untuk notifikasi error --}}
{{-- ================================================== --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <!-- {{-- ================================================== --}}
    {{-- TAB SWITCH: pilih mau pakai jQuery AJAX atau Axios --}}
    {{-- Fungsi switchTab() ada di bagian <script> bawah    --}}
    {{-- ================================================== --}} -->
    <div class="mb-3">
        <button class="btn btn-gradient-warning btn-rounded btn-sm active"
                id="btn-jquery"
                onclick="switchTab('jquery')">
            <i class="mdi mdi-jquery"></i>
            jQuery AJAX
        </button>

        <button class="btn btn-rounded btn-sm btn-outline-info"
                id="btn-axios"
                onclick="switchTab('axios')">
            <i class="mdi mdi-language-javascript"></i>
            Axios + Promise
        </button>
    </div>

    {{-- Badge penanda versi yang sedang aktif --}}
    <div class="mb-3">
        <label class="badge badge-gradient-warning" id="badge-jquery">
            Aktif: jQuery AJAX
        </label>
        {{-- Badge axios disembunyikan dulu, muncul kalau tab axios diklik --}}
        <label class="badge badge-gradient-info" id="badge-axios" style="display:none;">
            Aktif: Axios + Promise
        </label>
    </div>

    {{-- ================================================== --}}
    {{-- FORM DROPDOWN WILAYAH --}}
    {{-- Setiap dropdown trigger onChange → AJAX ke server  --}}
    {{-- ================================================== --}}

    <!-- {{-- LEVEL 1: Provinsi --}}
    {{-- Data provinsi langsung dari controller (tidak pakai AJAX) --}}
    {{-- karena provinsi dimuat saat halaman pertama kali dibuka  --}} -->
    <div class="form-group">
        <label>Provinsi</label>
        <select id="provinsi" class="form-control">
            <option value="0">-- Pilih Provinsi --</option>
            <!-- {{-- Loop data provinsi yang dikirim dari controller --}} -->
            @foreach($provinsi as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- LEVEL 2: Kota --}}
    {{-- Disabled dulu, baru aktif setelah provinsi dipilih --}}
    {{-- Diisi via AJAX setelah provinsi berubah            --}}
    <div class="form-group">
        <label>Kota / Kabupaten</label>
        <select id="kota" class="form-control" disabled>
            <option value="0">-- Pilih Kota --</option>
        </select>
    </div>

    {{-- LEVEL 3: Kecamatan --}}
    {{-- Disabled dulu, baru aktif setelah kota dipilih --}}
    <div class="form-group">
        <label>Kecamatan</label>
        <select id="kecamatan" class="form-control" disabled>
            <option value="0">-- Pilih Kecamatan --</option>
        </select>
    </div>

    {{-- LEVEL 4: Kelurahan --}}
    {{-- Disabled dulu, baru aktif setelah kecamatan dipilih --}}
    <div class="form-group">
        <label>Kelurahan / Desa</label>
        <select id="kelurahan" class="form-control" disabled>
            <option value="0">-- Pilih Kelurahan --</option>
        </select>
    </div>

    {{-- ================================================== --}}
    {{-- HASIL PILIHAN --}}
    {{-- Muncul otomatis setelah kelurahan dipilih          --}}
    {{-- ================================================== --}}
    <div class="alert alert-success" id="result-box" style="display:none;">
        <strong><i class="mdi mdi-map-marker"></i> Alamat Lengkap Terpilih:</strong>
        <p class="mb-0 mt-1" id="result-text"></p>
    </div>

</div>
</div>
</div>
</div>

{{-- ============================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================ --}}
<script>

// ==============================================================
// VARIABEL GLOBAL
// Menyimpan tab mana yang sedang aktif: 'jquery' atau 'axios'
// DEFAULT SAAT PERTAMA KALI HALAMAN DIBUKA : JQuery
// Lalu ketika user klik tombol tab, fungsi switchTab() dipanggil dan mengubah nilai variabel ini:
// ==============================================================
let activeTab = 'jquery';

// ==============================================================
// FUNGSI: switchTab(tab)
// Dipanggil saat tombol tab diklik
// Tugasnya: ganti tampilan badge & tombol, lalu reset semua dropdown
// ==============================================================
function switchTab(tab) {
    activeTab = tab; // nilai diubah jadi 'jquery' atau 'axios'

    // Ganti style tombol aktif/tidak aktif
    if (tab === 'jquery') {
        $('#btn-jquery').removeClass('btn-outline-warning').addClass('btn-gradient-warning');
        $('#btn-axios').removeClass('btn-gradient-info').addClass('btn-outline-info');
    } else {
        $('#btn-axios').removeClass('btn-outline-info').addClass('btn-gradient-info');
        $('#btn-jquery').removeClass('btn-gradient-warning').addClass('btn-outline-warning');
    }

    // Tampilkan badge sesuai tab aktif
    $('#badge-jquery').toggle(tab === 'jquery');
    $('#badge-axios').toggle(tab === 'axios');

    // Reset semua dropdown ke kondisi awal
    resetDropdown('#kota',      '-- Pilih Kota --');
    resetDropdown('#kecamatan', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    $('#provinsi').val('0');
    $('#result-box').hide();
}

// ==============================================================
// FUNGSI HELPER: resetDropdown(selector, placeholder)
// Mengosongkan dropdown dan men-disable-nya
// Dipanggil setiap kali dropdown di atasnya berubah
// ==============================================================
function resetDropdown(selector, placeholder) {
    $(selector)
        .html(`<option value="0">${placeholder}</option>`)
        .prop('disabled', true);
}

// ==============================================================
// FUNGSI HELPER: buildOptions(placeholder, data)
// Membuat string HTML option dari array data JSON
// Dipakai di bagian success AJAX maupun Axios
// ==============================================================
function buildOptions(placeholder, data) {
    let options = `<option value="0">${placeholder}</option>`;
    data.forEach(function(item) {
        options += `<option value="${item.id}">${item.name}</option>`;
    });
    return options;
}

// ==============================================================
// EVENT: Provinsi onChange
// Ketika provinsi dipilih → ambil data kota via AJAX/Axios
// Sekaligus reset kecamatan & kelurahan (poin d di modul)
// ==============================================================
$('#provinsi').on('change', function () {
    const provinsiId = $(this).val();

    // $this.val() = value dari option yang dipilih
    // value itu adalah {{ $p->id }} dari blade = "35"
    // jadi provinsiId = "35"

    // Reset dropdown level 2, 3, 4
    resetDropdown('#kota',      '-- Pilih Kota --');
    resetDropdown('#kecamatan', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    $('#result-box').hide();

    // Kalau pilih "-- Pilih Provinsi --", berhenti di sini
    if (provinsiId === '0') return;

    // ---- VERSI JQUERY AJAX ----
    if (activeTab === 'jquery') {
        $.ajax({
            url: '/wilayah/kota',   // route yang akan dipanggil
            method: 'GET',
            data: { provinsi_id: provinsiId }, // data yang dikirim ke server, misal pilih Jawa Timur jadi { provinsi_id: 35 }
            success: function (response) {
                // Kalau server balas status success, isi dropdown kota
                if (response.status === 'success') { // response ini yang menampung JSON dari server!. dia adalah variable penampung, dia otomatis berisi seluruh JSON yang dikriim dari controller
                    $('#kota')
                        .html(buildOptions('-- Pilih Kota --', response.data))
                        .prop('disabled', false); // aktifkan dropdown kota
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal mengambil data kota.', 'error');
            }
        });

    // ===================== VERSI AXIOS ================================================================================================
    // Sama persis fungsinya dengan jQuery AJAX
    // Bedanya: pakai Promise (.then / .catch) bukan callback
    } else {
        axios.get('/wilayah/kota', { params: { provinsi_id: provinsiId } })
            .then(function (response) {
                // response.data = isi JSON dari server
                // response.data.data = array kota (sesuai format response controller)
                if (response.data.status === 'success') {
                    $('#kota')
                        .html(buildOptions('-- Pilih Kota --', response.data.data))
                        .prop('disabled', false);
                }
            })
            .catch(function () {
                Swal.fire('Error!', 'Gagal mengambil data kota.', 'error');
            });
    }
});

// ==============================================================
// EVENT: Kota onChange
// Ketika kota dipilih → ambil data kecamatan
// Sekaligus reset kelurahan (poin e di modul)
// ==============================================================
$('#kota').on('change', function () {
    const kotaId = $(this).val();

    // Reset dropdown level 3 dan 4
    resetDropdown('#kecamatan', '-- Pilih Kecamatan --');
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    $('#result-box').hide();

    if (kotaId === '0') return;

    // ========= VERSI JQUERY AJAX =========================================
    if (activeTab === 'jquery') {
        $.ajax({
            url: '/wilayah/kecamatan',
            method: 'GET',
            data: { kota_id: kotaId },
            success: function (response) {
                if (response.status === 'success') {
                    $('#kecamatan')
                        .html(buildOptions('-- Pilih Kecamatan --', response.data))
                        .prop('disabled', false);
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal mengambil data kecamatan.', 'error');
            }
        });

    // ========== VERSI AXIOS ============================================
    } else {
        axios.get('/wilayah/kecamatan', { params: { kota_id: kotaId } })
            .then(function (response) {
                if (response.data.status === 'success') {
                    $('#kecamatan')
                        .html(buildOptions('-- Pilih Kecamatan --', response.data.data))
                        .prop('disabled', false);
                }
            })
            .catch(function () {
                Swal.fire('Error!', 'Gagal mengambil data kecamatan.', 'error');
            });
    }
});

// ==============================================================
// EVENT: Kecamatan onChange
// Ketika kecamatan dipilih → ambil data kelurahan
// ==============================================================
$('#kecamatan').on('change', function () {
    const kecamatanId = $(this).val();

    // Reset dropdown level 4
    resetDropdown('#kelurahan', '-- Pilih Kelurahan --');
    $('#result-box').hide();

    if (kecamatanId === '0') return;

    // ====== VERSI JQUERY AJAX =====================================
    if (activeTab === 'jquery') {
        $.ajax({
            url: '/wilayah/kelurahan',
            method: 'GET',
            data: { kecamatan_id: kecamatanId },
            success: function (response) {
                if (response.status === 'success') {
                    $('#kelurahan')
                        .html(buildOptions('-- Pilih Kelurahan --', response.data))
                        .prop('disabled', false);
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal mengambil data kelurahan.', 'error');
            }
        });

    // ========= VERSI AXIOS ===========================================
    } else {
        axios.get('/wilayah/kelurahan', { params: { kecamatan_id: kecamatanId } })
            .then(function (response) {
                if (response.data.status === 'success') {
                    $('#kelurahan')
                        .html(buildOptions('-- Pilih Kelurahan --', response.data.data))
                        .prop('disabled', false);
                }
            })
            .catch(function () {
                Swal.fire('Error!', 'Gagal mengambil data kelurahan.', 'error');
            });
    }
});

// ==============================================================
// EVENT: Kelurahan onChange
// Ketika kelurahan dipilih → tampilkan hasil alamat lengkap
// ==============================================================
$('#kelurahan').on('change', function () {
    // Deteksi apakah yg dipilih opsi default -- pilih kelurahan -- yg valuenya 0? kalau iya dia berhenti agar ringkasannya tidak muncul
    if ($(this).val() === '0') return;

    // Ambil teks dari option yang terpilih di setiap dropdown
    const prov = $('#provinsi option:selected').text();
    const kota = $('#kota option:selected').text();
    const kec  = $('#kecamatan option:selected').text();
    const kel  = $('#kelurahan option:selected').text();

    // Tampilkan hasil di result box
    $('#result-text').text(`${prov} → ${kota} → ${kec} → ${kel}`);
    $('#result-box').show();
});

</script>

@endsection