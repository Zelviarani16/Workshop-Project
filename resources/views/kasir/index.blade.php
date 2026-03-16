@extends('layouts.app')

@section('title', 'Kasir / Point of Sales')

@section('content')

{{-- Library --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ================================================== --}}
{{-- PAGE HEADER --}}
{{-- ================================================== --}}
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Kasir / Point of Sales</h3>
            <small class="text-muted">Transaksi penjualan barang</small>
        </div>
        {{-- Tab switch jQuery AJAX vs Axios --}}
        <div>
            <button class="btn btn-gradient-warning btn-rounded btn-sm"
                    id="btn-jquery" onclick="switchTab('jquery')">
                jQuery AJAX
            </button>
            <button class="btn btn-outline-info btn-rounded btn-sm"
                    id="btn-axios" onclick="switchTab('axios')">
                Axios
            </button>
        </div>
    </div>
</div>

<div class="row">

    {{-- ================================================== --}}
    {{-- KOLOM KIRI: Form Input Barang --}}
    {{-- ================================================== --}}
    <div class="col-md-5 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">

        <h4 class="card-title">Input Barang</h4>

        {{-- Badge versi aktif --}}
        <label class="badge badge-gradient-warning mb-3" id="badge-jquery">jQuery AJAX</label>
        <label class="badge badge-gradient-info mb-3" id="badge-axios" style="display:none;">Axios</label>

        {{-- INPUT KODE BARANG --}}
        {{-- Tekan Enter untuk trigger pencarian via AJAX --}}
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text"
                   id="kode_barang"
                   class="form-control"
                   placeholder="Ketik kode lalu tekan Enter...">
            {{-- Pesan status pencarian barang --}}
            <small id="status-barang" class="form-text"></small>
        </div>

        {{-- NAMA BARANG: readonly, diisi otomatis setelah barang ditemukan --}}
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text"
                   id="nama_barang"
                   class="form-control"
                   style="background:#f8f8f8;"
                   readonly
                   placeholder="Otomatis terisi...">
        </div>

        {{-- HARGA BARANG: readonly, diisi otomatis setelah barang ditemukan --}}
        <div class="form-group">
            <label>Harga Barang</label>
            <input type="text"
                   id="harga_barang"
                   class="form-control"
                   style="background:#f8f8f8;"
                   readonly
                   placeholder="Otomatis terisi...">
        </div>

        {{-- JUMLAH: default 1, bisa diubah kasir --}}
        <div class="form-group">
            <label>Jumlah</label>
            <input type="number"
                   id="jumlah"
                   class="form-control"
                   value="1"
                   min="1">
        </div>

        {{-- TOMBOL TAMBAHKAN --}}
        {{-- Disabled by default, aktif hanya kalau barang ditemukan & jumlah > 0 --}}
        <button class="btn btn-gradient-success btn-rounded w-100"
                id="btn-tambah"
                disabled
                onclick="tambahKeTable()">
            <i class="mdi mdi-plus"></i> Tambahkan
        </button>

    </div>
    </div>
    </div>

    {{-- ================================================== --}}
    {{-- KOLOM KANAN: Tabel Transaksi --}}
    {{-- ================================================== --}}
    <div class="col-md-7 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">

        <h4 class="card-title">Daftar Belanja</h4>

        <div class="table-responsive">
        <table class="table table-hover" id="table-transaksi">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tbody-transaksi">
                {{-- Baris transaksi akan ditambahkan via JavaScript --}}
                <tr id="row-kosong">
                    <td colspan="6" class="text-center text-muted">
                        Belum ada barang ditambahkan
                    </td>
                </tr>
            </tbody>
        </table>
        </div>

        {{-- TOTAL --}}
        <div class="d-flex justify-content-end align-items-center mt-3 mb-3">
            <h5 class="mb-0 mr-3">Total:</h5>
            <h4 class="mb-0 text-success font-weight-bold" id="total-harga">
                Rp 0
            </h4>
        </div>

        {{-- TOMBOL BAYAR --}}
        {{-- Disabled by default, aktif kalau ada minimal 1 item di tabel --}}
        <div class="d-flex justify-content-end">
            <button class="btn btn-gradient-primary btn-rounded"
                    id="btn-bayar"
                    disabled
                    onclick="prossBayar()">
                <i class="mdi mdi-cash-multiple"></i> Bayar
            </button>
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
// ==============================================================

// Menyimpan tab aktif: 'jquery' atau 'axios'
let activeTab = 'jquery';

// Array menyimpan semua item yang sudah ditambahkan ke tabel
// Format tiap item: { id_barang, nama, harga, jumlah, subtotal }
// cartItems adalah "memori sementara" di browser yang menyimpan semua barang yang sudah ditambahkan sebelum dibayar. Formatnya:
// cartItems = [
//     { id_barang:'BRG001', nama:'Pensil', harga:5000, jumlah:3, subtotal:15000 },
//     { id_barang:'BRG002', nama:'Buku',   harga:8000, jumlah:2, subtotal:16000 },
// ]
let cartItems = [];

// Menyimpan data barang yang sedang ditemukan (sementara)
let barangDitemukan = null;

// ==============================================================
// FUNGSI: switchTab(tab)
// Ganti mode antara jQuery AJAX dan Axios
// ==============================================================
function switchTab(tab) {
    activeTab = tab;

    if (tab === 'jquery') {
        $('#btn-jquery').removeClass('btn-outline-warning').addClass('btn-gradient-warning');
        $('#btn-axios').removeClass('btn-gradient-info').addClass('btn-outline-info');
    } else {
        $('#btn-axios').removeClass('btn-outline-info').addClass('btn-gradient-info');
        $('#btn-jquery').removeClass('btn-gradient-warning').addClass('btn-outline-warning');
    }

    $('#badge-jquery').toggle(tab === 'jquery');
    $('#badge-axios').toggle(tab === 'axios');
}

// ==============================================================
// FUNGSI: formatRupiah(angka)
// Mengubah angka menjadi format Rupiah
// Contoh: 15000 → "Rp 15.000"
// ==============================================================
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// ==============================================================
// FUNGSI: cekTombolTambah()
// Mengaktifkan/menonaktifkan tombol "Tambahkan"
// Tombol aktif HANYA jika: barang ditemukan DAN jumlah > 0
// ==============================================================
function cekTombolTambah() {
    const jumlah = parseInt($('#jumlah').val());
    const aktif  = barangDitemukan !== null && jumlah > 0;
    $('#btn-tambah').prop('disabled', !aktif);
}

// ==============================================================
// FUNGSI: resetInputBarang()
// Kosongkan semua input setelah barang ditambahkan
// ==============================================================
function resetInputBarang() {
    barangDitemukan = null;
    $('#kode_barang').val('').focus();
    $('#nama_barang').val('');
    $('#harga_barang').val('');
    $('#jumlah').val(1);
    $('#status-barang').text('').removeClass('text-success text-danger');
    $('#btn-tambah').prop('disabled', true);
}

// ==============================================================
// FUNGSI: hitungTotal()
// Hitung ulang total dari semua item di cartItems
// Dipanggil setiap kali ada perubahan di tabel
// ==============================================================
function hitungTotal() {
    let total = 0;
    cartItems.forEach(function(item) {
        total += item.subtotal;
    });
    $('#total-harga').text(formatRupiah(total));

    // Tombol Bayar aktif hanya kalau ada item
    $('#btn-bayar').prop('disabled', cartItems.length === 0);
}

// ==============================================================
// FUNGSI: renderTable()
// Render ulang seluruh isi tabel dari array cartItems
// Dipanggil setiap kali cartItems berubah
// ==============================================================
function renderTable() {
    const tbody = $('#tbody-transaksi');
    tbody.empty(); // kosongkan tabel dulu

    if (cartItems.length === 0) {
        // Tampilkan baris kosong kalau tidak ada item
        tbody.append(`
            <tr id="row-kosong">
                <td colspan="6" class="text-center text-muted">
                    Belum ada barang ditambahkan
                </td>
            </tr>
        `);
    } else {
        // Loop setiap item di cartItems, buat baris tabel
        cartItems.forEach(function(item, index) {
            tbody.append(`
                <tr>
                    <td>${item.id_barang}</td>
                    <td>${item.nama}</td>
                    <td>${formatRupiah(item.harga)}</td>

                        // Input jumlah bisa diubah langsung di tabel
                        // onchange memanggil ubahJumlah(index, nilai baru)

                    <td>
                        <input type="number"
                               class="form-control form-control-sm"
                               style="width:70px;"
                               value="${item.jumlah}"
                               min="1"
                               onchange="ubahJumlah(${index}, this.value)">
                    </td>

                    <td id="subtotal-${index}">${formatRupiah(item.subtotal)}</td>

                        // Tombol hapus memanggil hapusBaris(index)
                        
                    <td class="text-center">
                        <button class="btn btn-gradient-danger btn-sm btn-rounded"
                                onclick="hapusBaris(${index})">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    hitungTotal();
}

// ==============================================================
// FUNGSI: tambahKeTable()
// Dipanggil saat tombol "Tambahkan" diklik
// Cek apakah barang sudah ada di tabel:
//   - Sudah ada → update jumlah & subtotal saja
//   - Belum ada → tambah baris baru
// ==============================================================
function tambahKeTable() {
    if (!barangDitemukan) return;

    const jumlah = parseInt($('#jumlah').val());
    if (jumlah <= 0) return;

    // Spinner
    const btnTambah = $('#btn-tambah');
    btnTambah.prop('disabled', true);
    btnTambah.html('<span class="spinner-border spinner-border-sm mr-1"></span> Menambahkan...');

    // Cek apakah id_barang sudah ada di cartItems
    const indexExisting = cartItems.findIndex(
        item => item.id_barang === barangDitemukan.id_barang
    );

    if (indexExisting !== -1) {
        // Barang sudah ada → update jumlah dan subtotal saja
        cartItems[indexExisting].jumlah   += jumlah;
        cartItems[indexExisting].subtotal  = cartItems[indexExisting].jumlah
                                           * cartItems[indexExisting].harga;
    } else {
        // Barang belum ada → push item baru ke cartItems
        cartItems.push({
            id_barang : barangDitemukan.id_barang,
            nama      : barangDitemukan.nama,
            harga     : barangDitemukan.harga,
            jumlah    : jumlah,
            subtotal  : jumlah * barangDitemukan.harga,
        });
    }

    renderTable();
    // ← RESET TOMBOL SETELAH SELESAI
    btnTambah.html('<i class="mdi mdi-plus"></i> Tambahkan');
    // tombol tetap disabled, baru aktif lagi kalau barang ditemukan lagi
    resetInputBarang();
}

// ==============================================================
// FUNGSI: ubahJumlah(index, jumlahBaru)
// Dipanggil saat kasir mengubah jumlah langsung di tabel
// Update subtotal baris tersebut dan hitung ulang total
// ==============================================================
function ubahJumlah(index, jumlahBaru) {
    jumlahBaru = parseInt(jumlahBaru);
    if (jumlahBaru <= 0) return;

    cartItems[index].jumlah   = jumlahBaru;
    cartItems[index].subtotal = jumlahBaru * cartItems[index].harga;

    // Update subtotal di tabel tanpa render ulang semua
    $(`#subtotal-${index}`).text(formatRupiah(cartItems[index].subtotal));
    hitungTotal();
}

// ==============================================================
// FUNGSI: hapusBaris(index)
// Dipanggil saat tombol Hapus di baris tabel diklik
// ==============================================================
function hapusBaris(index) {
    cartItems.splice(index, 1); // hapus item dari array
    renderTable();
}

// ==============================================================
// EVENT: Input jumlah berubah → cek tombol Tambahkan
// ==============================================================
$('#jumlah').on('input', function() {
    cekTombolTambah();
});

// ==============================================================
// EVENT: Kode barang → tekan Enter → cari barang via AJAX
// Ini trigger utama untuk pencarian barang (poin b di modul)
// ==============================================================
$('#kode_barang').on('keydown', function(e) {
    // Hanya proses kalau yang ditekan adalah tombol Enter
    if (e.key !== 'Enter') return;

    const kode = $(this).val().trim(); // ambil kode yang diketik
    if (kode === '') return;

    // Reset dulu sebelum cari
    barangDitemukan = null;
    $('#nama_barang').val('');
    $('#harga_barang').val('');
    $('#btn-tambah').prop('disabled', true);
    $('#status-barang').text('Mencari...').removeClass('text-success text-danger'); // Tampilkan "Mencari..." sambil tunggu AJAX


    // ---- VERSI JQUERY AJAX ----
    if (activeTab === 'jquery') {
        $.ajax({
            url: '/kasir/cari-barang',
            method: 'GET',
            data: { kode_barang: kode },
            success: function(response) {
                if (response.status === 'success') {
                    // Barang ditemukan → isi field nama & harga
                    barangDitemukan = response.data;
                    $('#nama_barang').val(response.data.nama);
                    $('#harga_barang').val(formatRupiah(response.data.harga));
                    $('#status-barang')
                        .text('Barang ditemukan!')
                        .removeClass('text-danger')
                        .addClass('text-success');
                    cekTombolTambah();
                } else {
                    // Barang tidak ditemukan
                    $('#status-barang')
                        .text('Barang tidak ditemukan!')
                        .removeClass('text-success')
                        .addClass('text-danger');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
            }
        });

    // ---- VERSI AXIOS ----
    } else {
        axios.get('/kasir/cari-barang', { params: { kode_barang: kode } })
            .then(function(response) {
                if (response.data.status === 'success') {
                    barangDitemukan = response.data.data;
                    $('#nama_barang').val(response.data.data.nama);
                    $('#harga_barang').val(formatRupiah(response.data.data.harga));
                    $('#status-barang')
                        .text('Barang ditemukan!')
                        .removeClass('text-danger')
                        .addClass('text-success');
                    cekTombolTambah();
                } else {
                    $('#status-barang')
                        .text('Barang tidak ditemukan!')
                        .removeClass('text-success')
                        .addClass('text-danger');
                }
            })
            .catch(function() {
                Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
            });
    }
});

// ==============================================================
// FUNGSI: prossBayar()
// Dipanggil saat tombol "Bayar" diklik
// Kirim semua data cartItems ke server via AJAX/Axios
// ==============================================================
function prossBayar() {
    if (cartItems.length === 0) return;

    // Hitung total dari cartItems
    let total = cartItems.reduce((sum, item) => sum + item.subtotal, 0);

    // Data yang dikirim ke server
    const payload = {
        items : cartItems, // array semua barang
        total : total, // total keseluruhan
        // CSRF token wajib untuk method POST di Laravel
        _token: '{{ csrf_token() }}'
    };

    // ---- VERSI JQUERY AJAX ----
    if (activeTab === 'jquery') {
        // Spinner -  Disable tombol bayar sementara supaya tidak double klik
        const btnBayar = $('#btn-bayar');
        btnBayar.prop('disabled', true);
        btnBayar.html('<span class="spinner-border spinner-border-sm mr-1"></span> Memproses...');
       
        $.ajax({
            url    : '/kasir/bayar',
            method : 'POST',
            data   : JSON.stringify(payload),
            contentType: 'application/json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon : 'success',
                        title: 'Pembayaran Berhasil!',
                        text : `Transaksi #${response.data.id_penjualan} berhasil disimpan.`,
                    }).then(function() { // Dijalankan setelah kasir klik OK di sweet alert
                        // Kosongkan semua data setelah notifikasi ditutup
                        cartItems = [];
                        renderTable();
                        resetInputBarang();
                            // Reset tombol bayar ke kondisi semula
                        // Reset tombol bayar setelah sukses
                        btnBayar.prop('disabled', true).html('<i class="mdi mdi-cash-multiple"></i> Bayar');

                        // Reset tombol bayar setelah error
                        btnBayar.prop('disabled', false).html('<i class="mdi mdi-cash-multiple"></i> Bayar');

                    });
                }
            },
            error: function() {
                $('#btn-bayar').prop('disabled', false).html(
                    '<i class="mdi mdi-cash-multiple"></i> Bayar'
                );
                Swal.fire('Error!', 'Gagal menyimpan transaksi.', 'error');
            }
        });

    // ---- VERSI AXIOS ----
    } else {
        $('#btn-bayar').prop('disabled', true).text('Memproses...');

        // Axios otomatis set Content-Type: application/json
        // Untuk POST dengan CSRF di Laravel, kirim token di header
        axios.post('/kasir/bayar', payload, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(function(response) {
            if (response.data.status === 'success') {
                Swal.fire({
                    icon : 'success',
                    title: 'Pembayaran Berhasil!',
                    text : `Transaksi #${response.data.data.id_penjualan} berhasil disimpan.`,
                }).then(function() {
                    // Kosongkan semua data setelah notifikasi ditutup
                    cartItems = [];
                    renderTable();
                    resetInputBarang();
                });
            }
        })
        .catch(function() {
            $('#btn-bayar').prop('disabled', false).html(
                '<i class="mdi mdi-cash-multiple"></i> Bayar'
            );
            Swal.fire('Error!', 'Gagal menyimpan transaksi.', 'error');
        });
    }
}

</script>

@endsection
```

---

<!-- ## Ringkasan File yang Dibuat/Diubah
```
app/Http/Controllers/KasirController.php  ← BUAT BARU
app/Models/Penjualan.php                  ← BUAT BARU
app/Models/PenjualanDetail.php            ← BUAT BARU
app/Models/Barang.php                     ← UPDATE (cek & sesuaikan)
database/migrations/xxxx_penjualan.php    ← BUAT BARU
resources/views/kasir/index.blade.php     ← BUAT BARU
routes/web.php                            ← TAMBAH ROUTE -->