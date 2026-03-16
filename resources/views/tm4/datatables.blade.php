@extends('layouts.app')
@section('title', 'Latihan JS — DataTables')

@push('style')
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    /* Tugas 3: cursor jadi pointer saat hover di baris tabel */
    #tabelBarang tbody tr { cursor: pointer; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Latihan JS — DataTables</h3>
            <small class="text-muted">Data tidak tersimpan ke database</small>
        </div>
    </div>
</div>

<div class="row">
<div class="col-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

    {{-- ── TUGAS 2: FORM TAMBAH ── --}}
    <h5 class="mb-3">Tambah Barang</h5>

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

    <!-- {{-- Button di LUAR form --}} -->
    <button type="button" id="btnTambah"
            class="btn btn-gradient-primary btn-rounded mb-4">
        <i class="mdi mdi-plus"></i> Tambah
    </button>

    <hr>

    <!-- {{-- ── TUGAS 2: DATATABLES + TUGAS 3: klik baris → modal ── --}} -->
    <h5 class="mb-3">Data Barang</h5>
    <div class="table-responsive">
        <table class="table table-hover" id="tabelBarang" style="width:100%">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>
</div>
</div>
</div>


<!-- {{-- ── TUGAS 3: MODAL EDIT & HAPUS ── --}} -->
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {

    var counter     = 1;
    var selectedRow = null;

    // Inisialisasi DataTables
    // Otomatis dapat fitur: search, sort, pagination
    var table = $('#tabelBarang').DataTable({
        language: {
            emptyTable: 'Belum ada data. Tambahkan barang di atas.',
            zeroRecords: 'Data tidak ditemukan'
        }
    });


    // ── TUGAS 2: Tambah Barang ────────────────────────────
    $('#btnTambah').on('click', function () {
        var form = document.getElementById('formTambah');
        var btn  = $('#btnTambah');

        // Tugas 1: validasi
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Tugas 1: spinner
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm mr-1"></span> Menambahkan...');

setTimeout(function(){
        var nama  = $('#inputNama').val();
        var harga = parseInt($('#inputHarga').val());
        var id    = 'BRG-' + String(counter).padStart(3, '0');
        counter++;

        // TAMBAH BARIS
        // Tabel biasa : $('#bodyTabel').append('<tr>...')
        // DataTables  : table.row.add([...]).draw().node()
        var rowNode = table.row.add([
            id,
            nama,
            'Rp ' + harga.toLocaleString('id-ID')
        ]).draw().node();
        // .draw()  → render ulang tabel supaya baris baru muncul tp jangan reset hal pagination
        // .node()  → ambil elemen <tr> yang baru dibuat

        // Simpan data asli di <tr> untuk dipakai saat klik
        $(rowNode)
            .data('id',    id)
            .data('nama',  nama)
            .data('harga', harga)
            .on('click', function () {
                bukaModal(this); 
            });

        // Tugas 2: kosongkan input
        $('#inputNama').val('');
        $('#inputHarga').val('');

        btn.prop('disabled', false);
        btn.html('<i class="mdi mdi-plus"></i> Tambah');
}, 500);
});


    // ── TUGAS 3: Buka Modal ───────────────────────────────
    function bukaModal(rowNode) {
        selectedRow = rowNode;

        $('#modalId').val($(rowNode).data('id'));
        $('#modalNama').val($(rowNode).data('nama'));
        $('#modalHarga').val($(rowNode).data('harga'));

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

        // UBAH
        // Tabel biasa : $(td).text('...')
        // DataTables  : table.row(node).data([...]).draw(false)
        // draw(false) → jangan reset halaman pagination
        table.row(selectedRow).data([
            $(selectedRow).data('id'),
            namaBaru,
            'Rp ' + hargaBaru.toLocaleString('id-ID')
        ]).draw(false);

        // Setelah draw, node berubah → ambil node baru & pasang ulang data + event
        var newNode = table.row(selectedRow).node();
        $(newNode)
            .data('id',    $(selectedRow).data('id'))
            .data('nama',  namaBaru)
            .data('harga', hargaBaru)
            .off('click')
            .on('click', function () { bukaModal(this); });

        // Tutup modal setelah berhasil
        $('#modalEditHapus').modal('hide');

        btn.prop('disabled', false);
        btn.html('<i class="mdi mdi-content-save"></i> Ubah');
    });


    // ── TUGAS 3: Hapus ─────────────────────────────────────
    $('#btnModalHapus').on('click', function () {

        // Tabel biasa : $(row).remove()
        // DataTables  : table.row(node).remove().draw()
        table.row(selectedRow).remove().draw();

        // Tutup modal setelah berhasil
        $('#modalEditHapus').modal('hide');
    });

});
</script>
@endpush