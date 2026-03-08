@extends('layouts.app')
@section('title', 'Latihan JS — Tabel Biasa')

@push('style')
<style>
    /* Tugas 3: cursor jadi pointer saat hover di baris tabel */
    #tabelBarang tbody tr { cursor: pointer; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Modul Js dan JQuery — Tabel Biasa</h3>
            <small class="text-muted">Data tidak tersimpan ke database</small>
        </div>
    </div>
</div>

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    <!-- TUGAS 2: FORM TAMBAH -->
    <h5 class="mb-3">Tambah Barang</h5>

     <!-- novalidate: validasi dikontrol JS -->
    <form id="formTambah" novalidate>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" id="inputNama"
                           placeholder="Masukkan nama barang" required>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" class="form-control" id="inputHarga"
                           placeholder="Masukkan harga" required min="1">
                </div>
            </div>
        </div>
    </form>

    <!-- Button di LUAR form -->
    <button type="button" id="btnTambah"
            class="btn btn-gradient-primary btn-rounded mb-4">
        <i class="mdi mdi-plus"></i> Tambah
    </button>

    <hr>

    <!-- TUGAS 2: TABEL + TUGAS 3: klik baris → modal -->
    <h5 class="mb-3">Data Barang</h5>
    <div class="table-responsive">
        <table class="table table-hover" id="tabelBarang">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody id="bodyTabel">
                <tr id="emptyRow">
                    <td colspan="3" class="text-center text-muted py-3">
                        Belum ada data. Tambahkan barang di atas.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
</div>
</div>
</div>


<!-- TUGAS 3: MODAL EDIT & HAPUS  -->
<div class="modal fade" id="modalEditHapus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit / Hapus Barang</h5>
            <button type="button" class="close" onclick="$('#modalEditHapus').modal('hide')">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formModal" novalidate>

                    <div class="form-group">
                        <label>ID Barang</label>
                        <!-- readonly: ID tidak bisa diubah  -->
                        <input type="text" class="form-control"
                               id="modalId" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control"
                               id="modalNama" placeholder="Nama barang" required>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" class="form-control"
                               id="modalHarga" placeholder="Harga" required min="1">
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <!-- Button di LUAR form -->
                <button type="button" id="btnModalHapus"
                        class="btn btn-gradient-danger btn-rounded">
                    <i class="mdi mdi-delete"></i> Hapus
                </button>
                <button type="button" id="btnModalUbah"
                        class="btn btn-gradient-primary btn-rounded">
                    <i class="mdi mdi-content-save"></i> Ubah
                </button>
<button type="button" onclick="$('#modalEditHapus').modal('hide')"
        class="btn btn-gradient-secondary btn-rounded">

                    Batal
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('script')
<script>
$(document).ready(function () {

    var counter    = 1;       // untuk ID berurutan
    var selectedRow = null;   // simpan baris yang diklik


    // ── TUGAS 2: Tambah Barang ────────────────────────────
    $('#btnTambah').on('click', function () {
        var form = document.getElementById('formTambah');
        var btn  = $('#btnTambah');

        // Tugas 1: cek validasi
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Tugas 1: spinner
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm mr-1"></span> Menambahkan...');

        var nama  = $('#inputNama').val();
        var harga = parseInt($('#inputHarga').val());
        var id    = 'BRG-' + String(counter).padStart(3, '0'); // BRG-001, BRG-002
        counter++;

        // Hapus baris kosong kalau masih ada
        $('#emptyRow').remove();

        // Buat baris baru, simpan data di data-* agar mudah diambil saat diklik
        var barisBaru = $('<tr>')
            .attr('data-id',    id)
            .attr('data-nama',  nama)
            .attr('data-harga', harga)
            .html(
                '<td>' + id + '</td>' +
                '<td>' + nama + '</td>' +
                '<td>Rp ' + harga.toLocaleString('id-ID') + '</td>'
            )
            .on('click', function () {
                bukaModal(this); // Tugas 3: klik baris → buka modal
            });

        $('#bodyTabel').append(barisBaru);

        // Tugas 2: kosongkan input setelah tambah
        $('#inputNama').val('');
        $('#inputHarga').val('');

        btn.prop('disabled', false);
        btn.html('<i class="mdi mdi-plus"></i> Tambah');
    });


    // ── TUGAS 3: Buka Modal ───────────────────────────────
    function bukaModal(row) {
        selectedRow = row; // simpan baris yang diklik

        // isi form modal dari data baris yang diklik
        $('#modalId').val($(row).data('id'));
        $('#modalNama').val($(row).data('nama'));
        $('#modalHarga').val($(row).data('harga'));

        $('#modalEditHapus').modal('show');
    }


    // ── TUGAS 3: Ubah ─────────────────────────────────────
    $('#btnModalUbah').on('click', function () {
        var form = document.getElementById('formModal');
        var btn  = $('#btnModalUbah');

        // Tugas 1: validasi
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Tugas 1: spinner
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm mr-1"></span> Mengubah...');

        var namaBaru  = $('#modalNama').val();
        var hargaBaru = parseInt($('#modalHarga').val());

        // Update data di atribut baris agar konsisten kalau diklik lagi
        $(selectedRow).data('nama',  namaBaru);
        $(selectedRow).data('harga', hargaBaru);

        // Update tampilan teks di tabel (TIDAK ke database)
        $(selectedRow).find('td:eq(1)').text(namaBaru);
        $(selectedRow).find('td:eq(2)').text('Rp ' + hargaBaru.toLocaleString('id-ID'));

        // Tutup modal setelah berhasil
        $('#modalEditHapus').modal('hide');

        btn.prop('disabled', false);
        btn.html('<i class="mdi mdi-content-save"></i> Ubah');
    });


    // ── TUGAS 3: Hapus ─────────────────────────────────────
    $('#btnModalHapus').on('click', function () {

        // Hapus baris dari tabel (TIDAK ke database)
        $(selectedRow).remove();

        // Kalau tabel kosong, tampilkan pesan kosong lagi
        if ($('#bodyTabel tr').length === 0) {
            $('#bodyTabel').append(
                '<tr id="emptyRow"><td colspan="3" class="text-center text-muted py-3">' +
                'Belum ada data. Tambahkan barang di atas.</td></tr>'
            );
        }

        // Tutup modal setelah berhasil
        $('#modalEditHapus').modal('hide');
    });

});
</script>
@endpush