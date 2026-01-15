<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Transaction;

class OrderCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pesanan Selesai')
            ->greeting('Halo Admin!')
            ->line('Pesanan dengan kode TRX-' . str_pad($this->transaction->id, 5, '0', STR_PAD_LEFT) . ' telah dikonfirmasi selesai oleh pelanggan.')
            ->line('Nama Pelanggan: ' . $this->transaction->customer->name)
            ->line('Total: Rp ' . number_format($this->transaction->total, 0, ',', '.'))
            ->action('Lihat Transaksi', url('/admin/transactions/' . $this->transaction->id))
            ->line('Terima kasih telah menggunakan aplikasi Toko Alzendi!');
    }
}
