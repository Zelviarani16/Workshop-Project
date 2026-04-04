<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id_barang
 * @property string $nama
 * @property int $harga
 * @property string $timestamp
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereIdBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereTimestamp($value)
 */
	class Barang extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $idbuku
 * @property string $kode
 * @property string $judul
 * @property string $pengarang
 * @property int $idkategori
 * @property-read \App\Models\Kategori $kategori
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku whereIdbuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku whereIdkategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Buku wherePengarang($value)
 */
	class Buku extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $iddetail_pesanan
 * @property int $idmenu
 * @property int $idpesanan
 * @property int $jumlah
 * @property int $harga
 * @property int $subtotal
 * @property string $timestamp
 * @property string|null $catatan
 * @property-read \App\Models\Menu $menu
 * @property-read \App\Models\Pesanan $pesanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereIddetailPesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereIdmenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereIdpesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereTimestamp($value)
 */
	class DetailPesanan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $idkategori
 * @property string $nama_kategori
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Buku> $buku
 * @property-read int|null $buku_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereIdkategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereNamaKategori($value)
 */
	class Kategori extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $regency_id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan whereRegencyId($value)
 */
	class Kecamatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $district_id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereName($value)
 */
	class Kelurahan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $province_id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kota query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kota whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kota whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kota whereProvinceId($value)
 */
	class Kota extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $idmenu
 * @property string $nama_menu
 * @property int $harga
 * @property string|null $path_gambar
 * @property int $idvendor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Vendor $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereIdmenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereIdvendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereNamaMenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu wherePathGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Menu whereUpdatedAt($value)
 */
	class Menu extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_penjualan
 * @property string $timestamp
 * @property int $total
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PenjualanDetail> $details
 * @property-read int|null $details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penjualan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penjualan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penjualan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penjualan whereIdPenjualan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penjualan whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penjualan whereTotal($value)
 */
	class Penjualan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $idpenjualan_detail
 * @property int $id_penjualan
 * @property string $id_barang
 * @property int $jumlah
 * @property int $subtotal
 * @property-read \App\Models\Barang $barang
 * @property-read \App\Models\Penjualan $penjualan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail whereIdBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail whereIdPenjualan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail whereIdpenjualanDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PenjualanDetail whereSubtotal($value)
 */
	class PenjualanDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $idpesanan
 * @property string $nama
 * @property string $timestamp
 * @property int $total
 * @property string $metode_bayar
 * @property int $status_bayar
 * @property string|null $snap_token
 * @property string|null $midtrans_order_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPesanan> $details
 * @property-read int|null $details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereIdpesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereMetodeBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereMidtransOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereSnapToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereStatusBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTotal($value)
 */
	class Pesanan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereName($value)
 */
	class Provinsi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $id_google
 * @property string|null $otp
 * @property string $role
 * @property int|null $guest_number
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGuestNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdGoogle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $idvendor
 * @property string $nama_vendor
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Menu> $menus
 * @property-read int|null $menus_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereIdvendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereNamaVendor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUserId($value)
 */
	class Vendor extends \Eloquent {}
}

