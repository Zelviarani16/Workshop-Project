<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    // -------------------------------------------------------
    // Webhook: dipanggil otomatis oleh Midtrans
    // saat status pembayaran berubah
    // Midtrans kirim Webhook via ngrok --> laravel update status
    // -------------------------------------------------------
    public function callback(Request $request)
    {
        // Setup Midtrans config
        Config::$serverKey    = config('midtrans.server_key');         // Kasih tahu library Server Key untuk verifikasi keamanan
        Config::$isProduction = config('midtrans.is_production');

        // Buat objek notifikasi dari Midtrans
        // Baca isi notifikasi dari Midtrans
        // Terima JSON dari Midtrans
        $notif = new Notification();

        $orderId           = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $fraudStatus       = $notif->fraud_status;
        $paymentType       = $notif->payment_type;

        // Cari pesanan berdasarkan midtrans_order_id di database
        $pesanan = Pesanan::where('midtrans_order_id', $orderId)->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // Cek status transaksi dari Midtrans
        // settlement = pembayaran berhasil dikonfirmasi
        // capture = pembayaran berhasil (untuk kartu kredit)
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            if ($fraudStatus == 'accept' || $fraudStatus == null) {
                // Update status pesanan menjadi LUNAS (1)
                $pesanan->update(['status_bayar' => 1]);
            }
        } elseif ($transactionStatus == 'pending') {
            // Masih menunggu pembayaran, tidak perlu update
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            // Pembayaran gagal/expired
            $pesanan->update(['status_bayar' => 2]); // 2 = gagal/expired
        }

        // Balas ke Midtrans: "ok, sudah aku proses!"
        return response()->json(['message' => 'OK']);
    }
} 