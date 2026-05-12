<style>
@page { size: A6; margin: 10mm; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; text-align: center; }
.nama { font-size: 14pt; font-weight: bold; margin-bottom: 4mm; }
.alamat { font-size: 9pt; color: #666; margin-bottom: 4mm; }
.barcode-code { font-size: 8pt; color: #888; margin-top: 2mm; }
qrbox: function(w, h) {
    return { width: Math.min(w * 0.9, 350), height: 150 }; // lebih tinggi
}
</style>

<div class="nama">{{ $toko->nama_toko }}</div>
<div class="alamat">{{ $toko->alamat ?? '' }}</div>
<img src="data:image/png;base64,{{ $barcode64 }}"
     style="width:80mm; height:20mm; display:block; margin:0 auto;">
<div class="barcode-code">{{ $toko->barcode }}</div>