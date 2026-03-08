@extends('layouts.app')
@section('title', 'Latihan JS — Select & Select2')

@push('style')
{{-- CSS Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
      rel="stylesheet"/>
<style>
    /* Sesuaikan tinggi Select2 dengan input Bootstrap */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        color: #495057;
    }
    .select2-container { width: 100% !important; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Latihan JS — Select & Select2</h3>
            <small class="text-muted">Tugas 4 — Select dinamis</small>
        </div>
    </div>
</div>

<div class="row">
<!-- 
    {{-- =====================================================
         CARD 1 : SELECT BIASA
         Modul: "Card pertama beri judul Select,
                 gunakan element select biasa"
    ===================================================== --}} -->
    <div class="col-md-6 grid-margin stretch-card">
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Select</h5>
        </div>

        <div class="card-body">

            {{-- Input tambah kota --}}
            <div class="form-group">
                <label>Tambah Kota</label>
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="inputKota1"
                           placeholder="Ketik nama kota">
                    <div class="input-group-append">
                        <button type="button"
                                class="btn btn-gradient-primary"
                                id="btnTambahKota1">
                            Tambah
                        </button>
                    </div>
                </div>
            </div>

            <!-- {{-- Select biasa --}}
            {{--
                Modul: "Form select kota adalah sebuah element input select"
                Modul: "Jadikan nama kota sebagai value dan nilai tampil"
                onchange → event yang terpicu saat pilihan berubah
            --}} -->
            <div class="form-group">
                <label>Pilih Kota</label>
                <select class="form-control" id="selectKota1">
                    <option value="">-- Pilih Kota --</option>
                </select>
            </div>

            <!-- {{--
                Modul: "Setiap kota yang terpilih akan ditampilkan
                         pada Kota Terpilih"
            --}} -->
            <div class="form-group">
                <label>Kota Terpilih</label>
                <div class="form-control bg-light" id="kotaTerpilih1"
                     style="min-height:38px; color:#6c757d;">
                    Belum ada kota dipilih
                </div>
            </div>

        </div>
    </div>
    </div>

<!-- 
    {{-- =====================================================
         CARD 2 : SELECT2
         Modul: "Card kedua beri judul Select 2,
                 gunakan element select2"
         Bedanya dari Card 1:
         - Dropdown punya kotak pencarian otomatis
         - Cara inisialisasi pakai $('#selectKota2').select2()
         - Cara tambah option berbeda (pakai .trigger('change'))
         - Event change pakai .on('change') bukan onchange HTML
    ===================================================== --}} -->
    <div class="col-md-6 grid-margin stretch-card">
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Select 2</h5>
        </div>

        <div class="card-body">

            {{-- Input tambah kota --}}
            <div class="form-group">
                <label>Tambah Kota</label>
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="inputKota2"
                           placeholder="Ketik nama kota">
                    <div class="input-group-append">
                        <button type="button"
                                class="btn btn-gradient-success"
                                id="btnTambahKota2">
                            Tambah
                        </button>
                    </div>
                </div>
            </div>

            {{-- Select2 (tampilannya sama di HTML, bedanya di JS) --}}
            <div class="form-group">
                <label>Pilih Kota</label>
                <select class="form-control" id="selectKota2">
                    <option value="">-- Pilih Kota --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kota Terpilih</label>
                <div class="form-control bg-light" id="kotaTerpilih2"
                     style="min-height:38px; color:#6c757d;">
                    Belum ada kota dipilih
                </div>
            </div>

        </div>
    </div>
    </div>

</div>

@endsection


@push('script')
<!-- {{-- JS Select2 --}} -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    // ── Inisialisasi Select2 ──────────────────────────────────
    // Card 1 (select biasa) tidak perlu inisialisasi apapun
    // Card 2 (select2) WAJIB dipanggil .select2() agar aktif
    // Efeknya: dropdown Card 2 punya kotak pencarian otomatis
    $('#selectKota2').select2({
        placeholder: '-- Pilih Kota --',
        allowClear: true   // muncul tombol X untuk reset pilihan
    });


    // ── CARD 1 : Tambah kota ke select biasa ─────────────────
    $('#btnTambahKota1').on('click', function () {
        var kota = $('#inputKota1').val().trim();

        if (kota === '') {
            alert('Nama kota tidak boleh kosong!');
            return;
        }

        // Tambah <option> baru ke select
        // Modul: "Jadikan nama kota sebagai value dan nilai tampil"
        // value = nama kota, text = nama kota
        $('#selectKota1').append(
            '<option value="' + kota + '">' + kota + '</option>'
        );

        $('#inputKota1').val(''); // kosongkan input
    });


    // ── CARD 1 : Tampilkan kota terpilih (select biasa) ──────
    // Select biasa pakai event .on('change')
    $('#selectKota1').on('change', function () {
        var pilihan = $(this).val();

        if (pilihan === '' || pilihan === null) {
            $('#kotaTerpilih1').text('Belum ada kota dipilih').css('color', '#6c757d');
        } else {
            // Modul: "Setiap kota yang terpilih ditampilkan pada Kota Terpilih"
            $('#kotaTerpilih1').text(pilihan).css('color', '#3f51b5');
        }
    });


    // ── CARD 2 : Tambah kota ke Select2 ──────────────────────
    $('#btnTambahKota2').on('click', function () {
        var kota = $('#inputKota2').val().trim();

        if (kota === '') {
            alert('Nama kota tidak boleh kosong!');
            return;
        }

        // Cara tambah option ke Select2 BERBEDA dari select biasa!
        //
        // Select biasa : $('#select').append('<option>...')
        //
        // Select2      : harus pakai new Option() lalu .trigger('change')
        //                .trigger('change') → memberitahu Select2
        //                bahwa ada perubahan di option
        var option = new Option(kota, kota, false, false);
        $('#selectKota2').append(option).trigger('change');

        $('#inputKota2').val(''); // kosongkan input
    });


    // ── CARD 2 : Tampilkan kota terpilih (Select2) ───────────
    // Select2 juga pakai .on('change'), sama seperti select biasa
    // Bedanya $(this).val() pada Select2 bisa return null kalau dikosongkan
    $('#selectKota2').on('change', function () {
        var pilihan = $(this).val();

        if (pilihan === '' || pilihan === null) {
            $('#kotaTerpilih2').text('Belum ada kota dipilih').css('color', '#6c757d');
        } else {
            $('#kotaTerpilih2').text(pilihan).css('color', '#1bcfb4');
        }
    });

});
</script>
@endpush