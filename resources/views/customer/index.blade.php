@extends('layouts.guest')
@section('title', 'Pesan Makanan')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Snap.js dari Midtrans untuk tampilkan popup QR --}}
{{-- mengunduh library snap.js dari server Midtrans ke browser kita --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h3 class="page-title mb-0">Pesan Makanan Kantin</h3>
            <small class="text-muted">Pilih vendor dan menu, lalu bayar dengan QRIS</small>
        </div>
    </div>
</div>

<div class="row">

    {{-- KOLOM KIRI: Form Pemesanan --}}
    <div class="col-md-5 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        <h4 class="card-title">Pilih Menu</h4>

        {{-- DROPDOWN LEVEL 1: Vendor dirender daru data yg dikirim dari controller --}}
        <div class="form-group">
            <label>Pilih Vendor / Kantin</label>
            <select id="vendor" class="form-control">
                <option value="0">-- Pilih Vendor --</option>
                @foreach($vendors as $v)
                        {{-- value = idvendor, dipakai AJAX untuk filter menu --}}
                    <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                @endforeach
            </select>
        </div>

        {{-- DROPDOWN LEVEL 2: Menu (diisi via AJAX) --}}
        <div class="form-group">
            <label>Pilih Menu</label>
            {{-- AWALNYA DISABLED --}}
            <select id="menu" class="form-control" disabled>
                <option value="0">-- Pilih Menu --</option>
            </select>
            <small id="status-menu" class="form-text text-muted"></small>
        </div>

        {{-- Harga otomatis terisi --}}
        <div class="form-group">
            <label>Harga</label>
            <input type="text" id="harga_menu" class="form-control"
                   readonly style="background:#f8f8f8;"
                   placeholder="Otomatis terisi...">
        </div>

        {{-- Jumlah --}}
        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" id="jumlah" class="form-control" value="1" min="1">
        </div>

        {{-- Catatan opsional --}}
        <div class="form-group">
            <label>Catatan <small class="text-muted">(opsional)</small></label>
            <input type="text" id="catatan" class="form-control"
                   placeholder="Misal: pedas, tidak pakai bawang...">
        </div>

        {{-- Tombol Tambahkan --}}
        <button class="btn btn-gradient-success btn-rounded w-100"
                id="btn-tambah" disabled onclick="tambahKeTable()">
            <i class="mdi mdi-plus"></i> Tambahkan
        </button>

    </div>
    </div>
    </div>

    {{-- KOLOM KANAN: Tabel Pesanan --}}
    <div class="col-md-7 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        <h4 class="card-title">Daftar Pesanan</h4>

        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Subtotal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tbody-pesanan">
                <tr id="row-kosong">
                    <td colspan="6" class="text-center text-muted">
                        Belum ada menu dipilih
                    </td>
                </tr>
            </tbody>
        </table>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-3 mb-3">
            <h5 class="mb-0 mr-3">Total:</h5>
            <h4 class="mb-0 text-success font-weight-bold" id="total-harga">Rp 0</h4>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-gradient-primary btn-rounded"
                    id="btn-bayar" disabled onclick="prosesCheckout()">
                <i class="mdi mdi-qrcode-scan"></i> Pesan & Bayar QRIS
            </button>
        </div>

    </div>
    </div>
    </div>

</div>

<script>
// ============================================================
// VARIABEL GLOBAL
// ============================================================
let cartItems    = []; // array semua item pesanan
let menuDipilih  = null; // data menu yang sedang dipilih

// ============================================================
// FUNGSI HELPER
// ============================================================
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function cekTombolTambah() {
    const jumlah = parseInt($('#jumlah').val());
    const aktif  = menuDipilih !== null && jumlah > 0;
    $('#btn-tambah').prop('disabled', !aktif);
}

function resetFormMenu() {
    menuDipilih = null;
    $('#menu').val('0');
    $('#harga_menu').val('');
    $('#jumlah').val(1);
    $('#catatan').val('');
    $('#btn-tambah').prop('disabled', true);
}

function hitungTotal() {
    let total = 0;
    cartItems.forEach(item => total += item.subtotal);
    $('#total-harga').text(formatRupiah(total));
    $('#btn-bayar').prop('disabled', cartItems.length === 0);
}

function renderTable() {
    const tbody = $('#tbody-pesanan');
    tbody.empty();

    if (cartItems.length === 0) {
        tbody.append(`<tr id="row-kosong">
            <td colspan="6" class="text-center text-muted">Belum ada menu dipilih</td>
        </tr>`);
    } else {
        cartItems.forEach(function(item, index) {
            tbody.append(`
                <tr>
                    <td>${item.nama_menu}</td>
                    <td>${formatRupiah(item.harga)}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               style="width:70px;" value="${item.jumlah}" min="1"
                               onchange="ubahJumlah(${index}, this.value)">
                    </td>
                    <td><small>${item.catatan || '-'}</small></td>
                    <td id="subtotal-${index}">${formatRupiah(item.subtotal)}</td>
                    <td class="text-center">
                        <button class="btn btn-gradient-danger btn-sm btn-rounded"
                                onclick="hapusBaris(${index})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }
    hitungTotal();
}

function ubahJumlah(index, jumlahBaru) {
    jumlahBaru = parseInt(jumlahBaru);
    if (jumlahBaru <= 0) return;
    cartItems[index].jumlah   = jumlahBaru;
    cartItems[index].subtotal = jumlahBaru * cartItems[index].harga;
    $(`#subtotal-${index}`).text(formatRupiah(cartItems[index].subtotal));
    hitungTotal();
}

function hapusBaris(index) {
    cartItems.splice(index, 1);
    renderTable();
}

// ============================================================
// EVENT: Vendor onChange -> ambil menu via AJAX
// ============================================================
$('#vendor').on('change', function() {
    const idvendor = $(this).val();
    // this --> elemen #vendor yg berubah
    // .val() --> ambil value dari option yg dipilih

    // Reset dropdown menu, krn kalau vendor diganti menu lama harus hilang dulu sebelum menu baru dimuat
    $('#menu').html('<option value="0">-- Pilih Menu --</option>').prop('disabled', true);
    menuDipilih = null;
    $('#harga_menu').val('');
    $('#btn-tambah').prop('disabled', true);
    $('#status-menu').text('');

    if (idvendor === '0') return;

    $('#status-menu').text('Memuat menu...');

    // Browser kirim request ke /pesan/menu?idvendor=... lalu masuk web.php
    $.ajax({
        url: '/pesan/menu',
        method: 'GET',
        data: { idvendor: idvendor },
        success: function(response) {
            if (response.status === 'success' && response.data.length > 0) {
                let options = '<option value="0">-- Pilih Menu --</option>';
                response.data.forEach(function(item) {
                    options += `<option value="${item.idmenu}"
                                        data-harga="${item.harga}"
                                        data-nama="${item.nama_menu}">
                                    ${item.nama_menu} - ${formatRupiah(item.harga)}
                                </option>`;
                });
                $('#menu').html(options).prop('disabled', false);
                $('#status-menu').text('');
            } else {
                $('#status-menu').text('Tidak ada menu tersedia untuk vendor ini.');
            }
        },
        error: function() {
            Swal.fire('Error!', 'Gagal memuat menu.', 'error');
        }
    });
});

// ============================================================
// EVENT: Menu onChange -> isi harga otomatis
// ============================================================
$('#menu').on('change', function() {
    const selected = $(this).find('option:selected');
    const idmenu   = $(this).val();

    if (idmenu === '0') {
        menuDipilih = null;
        $('#harga_menu').val('');
        $('#btn-tambah').prop('disabled', true);
        return;
    }

    // Tidak perlu AJAX lagi! Data sudah ada di data-attribute
    menuDipilih = {
        idmenu   : idmenu,
        nama_menu: selected.data('nama'),
        harga    : parseInt(selected.data('harga')),
    };

    // Tampilkan harga otomatis di field read only
    $('#harga_menu').val(formatRupiah(menuDipilih.harga));
    cekTombolTambah();
});

// ============================================================
// EVENT: Jumlah berubah -> cek tombol tambah
// ============================================================
$('#jumlah').on('input', function() { cekTombolTambah(); });

// ============================================================
// FUNGSI: Tambah ke tabel pesanan
// ============================================================
function tambahKeTable() {
    if (!menuDipilih) return;
    const jumlah  = parseInt($('#jumlah').val());
    const catatan = $('#catatan').val();
    if (jumlah <= 0) return;

    const btnTambah = $('#btn-tambah');
    btnTambah.prop('disabled', true);
    btnTambah.html('<span class="spinner-border spinner-border-sm mr-1"></span> Menambahkan...');

    // cartItems = memori sementara di browser
    // Menyimpan semua barang sebelum dikirim ke server
    // Cek apakah menu yang sama sudah ada
    const indexExisting = cartItems.findIndex(
        item => item.idmenu === menuDipilih.idmenu
    );

    if (indexExisting !== -1) {
        // Menu sama sudah ada → update jumlah saja
        cartItems[indexExisting].jumlah   += jumlah;
        cartItems[indexExisting].subtotal  = cartItems[indexExisting].jumlah
                                           * cartItems[indexExisting].harga;
    } else {
        // Menu baru → push ke array
        cartItems.push({
            idmenu   : menuDipilih.idmenu,
            nama_menu: menuDipilih.nama_menu,
            harga    : menuDipilih.harga,
            jumlah   : jumlah,
            subtotal : jumlah * menuDipilih.harga,
            catatan  : catatan,
        });
    }

    // tampilkan cartItems ke tabel HTML
    renderTable(); 
    btnTambah.html('<i class="mdi mdi-plus"></i> Tambahkan');
    // // kosongkan form input
    resetFormMenu();
}

// ============================================================
// FUNGSI: Checkout - simpan pesanan + tampilkan QR Midtrans
// ============================================================
// AJAX POST
function prosesCheckout() {
    if (cartItems.length === 0) return;

    const total    = cartItems.reduce((sum, item) => sum + item.subtotal, 0);
    const btnBayar = $('#btn-bayar');

    // Spinner: cegah double click
    btnBayar.prop('disabled', true);
    btnBayar.html('<span class="spinner-border spinner-border-sm mr-1"></span> Memproses...');

    // Kirim data ke server
    axios.post('/pesan/checkout', {
        items : cartItems,
        total : total,
    }, {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(function(response) {
        if (response.data.status === 'success') {
            const snapToken = response.data.snap_token;
            const idpesanan = response.data.idpesanan;

            // Reset tombol dulu
            btnBayar.prop('disabled', false)
                    .html('<i class="mdi mdi-qrcode-scan"></i> Pesan & Bayar QRIS');

            // Tampilkan popup QR Midtrans menggunakan Snap.js
            // snap.pay() adalah fungsi dari library Midtrans
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    // Pembayaran berhasil
                    Swal.fire({
                        icon : 'success',
                        title: 'Pembayaran Berhasil!',
                        text : `Pesanan kamu telah dikonfirmasi.`,
                    }).then(function() {
                        // Redirect ke halaman status
                        window.location.href = '/pesan/status/' + idpesanan;
                    });
                },
                onPending: function(result) {
                    // Menunggu pembayaran
                    Swal.fire({
                        icon : 'info',
                        title: 'Menunggu Pembayaran',
                        text : 'Silahkan selesaikan pembayaran QRIS kamu.',
                    });
                },
                onError: function(result) {
                    Swal.fire('Gagal!', 'Pembayaran gagal. Silahkan coba lagi.', 'error');
                },
                onClose: function() {
                    // Popup ditutup tanpa bayar
                    Swal.fire({
                        icon : 'warning',
                        title: 'Pembayaran Belum Selesai',
                        text : 'Kamu menutup halaman pembayaran. Pesanan tetap tersimpan.',
                    });
                }
            });
        }
    })
    .catch(function(error) {
        btnBayar.prop('disabled', false)
                .html('<i class="mdi mdi-qrcode-scan"></i> Pesan & Bayar QRIS');
        Swal.fire('Error!', 'Gagal memproses pesanan.', 'error');
    });
}


</script>

@endsection