@extends('layouts.app')

@section('title', 'QRIS Payment Preview')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📱 QRIS Pembayaran Toko Alzendi</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-4">
                        Gunakan QR Code di bawah untuk melakukan pembayaran
                    </p>
                    
                    <!-- QR Code -->
                    <div style="margin: 30px 0; padding: 30px; background-color: #f8f9fa; border-radius: 8px; border: 2px solid #17a2b8;">
                        <img src="{{ asset('qris.jpg') }}" class="img-fluid mx-auto d-block" style="max-width:350px;">
                    </div>
                    
                    <!-- Merchant Info -->
                    <div class="alert alert-info">
                        <div style="font-size: 14px; margin-bottom: 5px;">
                            <strong>Penerima Pembayaran:</strong>
                        </div>
                        <div style="font-size: 16px; font-weight: bold;">
                            TOKO ALZENDI
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div style="margin-top: 30px; text-align: left; background-color: #fff3cd; padding: 20px; border-radius: 8px;">
                        <div style="font-weight: bold; margin-bottom: 15px; color: #856404;">📋 Langkah-Langkah Pembayaran:</div>
                        <ol style="margin-bottom: 0; color: #856404;">
                            <li>Buka aplikasi mobile banking atau e-wallet favorit Anda</li>
                            <li>Cari dan pilih fitur "Scan QRIS" atau "Transfer via QRIS"</li>
                            <li>Arahkan kamera ke QR Code di atas</li>
                            <li>Verifikasi nominal pembayaran yang muncul</li>
                            <li>Masukkan PIN atau konfirmasi lainnya</li>
                            <li>Tunggu notifikasi pembayaran berhasil</li>
                            <li>Simpan bukti pembayaran untuk referensi</li>
                        </ol>
                    </div>
                    
                    <!-- Download Button -->
                    <div style="margin-top: 30px;">
                        <a href="{{ route('qris.download') }}" class="btn btn-primary btn-lg" download>
                            📥 Download QRIS Code (PNG)
                        </a>
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-light mt-4 text-muted" style="font-size: 12px;">
                        <p style="margin-bottom: 5px;">
                            <strong>Catatan:</strong> QRIS Code ini dapat digunakan oleh semua metode pembayaran digital yang tersedia di Indonesia.
                        </p>
                        <p style="margin-bottom: 0;">
                            Masukkan nominal pembayaran sesuai dengan transaksi Anda pada aplikasi pembayaran.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
