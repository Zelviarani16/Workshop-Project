<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    size: A4 portrait;
    margin: 30px 40px;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 12pt;
    line-height: 1.6;
    position: relative;
}

/* ===== WATERMARK ===== */
.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 420px;
    transform: translate(-50%, -50%);
    opacity: 0.12;
    z-index: -1;
}

/* ===== HEADER / KOP ===== */
.kop {
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
}

.kop img {
    width: 100%;
}

.garis {
    border-bottom: 2px solid black;
    margin-top: 6px;
    margin-bottom: 25px;
}

/* ===== TEXT ===== */
.right {
    text-align: right;
}

.content {
    text-align: justify;
}

.table-info {
    margin-left: 60px;
    margin-top: 10px;
    margin-bottom: 20px;
}

.table-info td {
    padding: 2px 6px;
    vertical-align: top;
}

/* ===== TTD ===== */
.ttd {
    width: 100%;
    margin-top: 70px;
}

.ttd td {
    width: 50%;
    text-align: center;
    vertical-align: top;
}

.nama-ttd {
    margin-top: 80px;
    font-weight: bold;
}
</style>
</head>

<body>

{{-- ✅ WATERMARK UNAIR --}}
<!-- <img src="{{ public_path('assets/images/watermark-unair.png') }}" class="watermark"> -->

{{-- ✅ KOP SURAT --}}
<!-- <div class="kop">
    <img src="{{ public_path('assets/images/kop-unair.png') }}">
</div> -->
<div class="garis"></div>

{{-- ✅ TANGGAL KANAN --}}
<p class="right">Surabaya, {{ $tanggal ?? '....................' }}</p>

{{-- ✅ NOMOR --}}
<p>
Nomor &nbsp;&nbsp;&nbsp;&nbsp;: {{ $nomor ?? '-' }} <br>
Lampiran : {{ $lampiran ?? '-' }} <br>
Hal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Undangan
</p>

{{-- ✅ TUJUAN --}}
<p>
Yth.<br>
{!! nl2br(e($tujuan ?? '........................................')) !!}<br><br>
di Tempat
</p>

<div class="content">
<p>Dengan hormat,</p>

<p>
Sehubungan dengan akan diselenggarakannya kegiatan
<strong>{{ $nama_kegiatan ?? '................................' }}</strong>
yang akan dilaksanakan pada:
</p>

<table class="table-info">
<tr>
    <td>Hari/tanggal</td>
    <td>:</td>
    <td>{{ $hari_tanggal ?? '-' }}</td>
</tr>
<tr>
    <td>Waktu</td>
    <td>:</td>
    <td>{{ $waktu ?? '-' }}</td>
</tr>
<tr>
    <td>Tempat</td>
    <td>:</td>
    <td>{{ $tempat ?? '-' }}</td>
</tr>
</table>

<p>
Maka kami selaku panitia pelaksana kegiatan tersebut bermaksud untuk
mengundang <strong>{{ $nama_tamu ?? 'Bapak/Ibu' }}</strong>
sebagai tamu undangan dalam kegiatan tersebut.
</p>

<p>
Demikian surat undangan ini kami buat, atas perhatian dan kerjasamanya
kami ucapkan terima kasih.
</p>
</div>

{{-- ✅ TANDA TANGAN --}}
<table class="ttd">
<tr>
    <td>
        Menyetujui,<br>
        Sekretaris HIMA D4 Teknik Informatika<br>

        <div class="nama-ttd">
            Evika Pitaloka<br>
            NIM. 434231100
        </div>
    </td>

    <td>
        Ketua Pelaksana Kegiatan,<br>
        TEKNOVISTAFEST 2025

        <div class="nama-ttd">
            Zelvia Rani Febrianti D. S.<br>
            NIM. 434241018
        </div>
    </td>
</tr>
</table>

</body>
</html>