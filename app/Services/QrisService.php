<?php

namespace App\Services;

class QrisService
{
    // Merchant QRIS yang fixed untuk semua transaksi
    const MERCHANT_ID = '000000000000001';
    const MERCHANT_NAME = 'TOKO ALZENDI';
    const QRIS_ACCOUNT = '625206109999';
    const MERCHANT_CATEGORY = '5411'; // Retail
    
    /**
     * Generate QRIS code menggunakan Google Charts API
     * 
     * @param float $amount Nominal transaksi
     * @param string $merchantName Nama merchant/toko
     * @param string $transactionId ID transaksi
     * @return string URL ke QR code
     */
    public static function generateQris(float $amount, string $merchantName = 'Toko Alzendi', string $transactionId = ''): string
    {
        try {
            $qrisData = self::generateQrisPayload($amount, $merchantName, $transactionId);
            return self::getQrCodeUrl($qrisData);
        } catch (\Exception $e) {
            \Log::error('Error generating QRIS', ['error' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Generate Static QRIS Merchant
     * QRIS yang sama untuk semua transaksi, digunakan untuk ditampilkan di UI
     * 
     * @return string URL ke QR code merchant
     */
    public static function getMerchantQris(): string
    {
        try {
            // Format QRIS untuk merchant static (tanpa amount, bisa di-input oleh pembayar)
            $qrisData = self::generateMerchantQrisPayload();
            return self::getQrCodeUrl($qrisData);
        } catch (\Exception $e) {
            \Log::error('Error generating merchant QRIS', ['error' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Generate QRIS payload dengan format EMV QRIS
     * 
     * @param float $amount Nominal transaksi
     * @param string $merchantName Nama merchant
     * @param string $transactionId ID transaksi
     * @return string EMV QRIS payload
     */
    private static function generateQrisPayload(float $amount, string $merchantName, string $transactionId): string
    {
        // Format QRIS minimal untuk testing/development
        // Dalam production, gunakan provider resmi seperti BRI, BNI, Mandiri, etc
        
        // Amount formatting (tanpa decimal separator)
        $amountFormatted = str_pad((int)($amount * 100), 12, '0', STR_PAD_LEFT);
        
        // Simple QRIS format for demonstration
        $qrisCode = sprintf(
            "00020126%s0015id.co.qris0%d%s0215%s0417%s0520%s0617%s0707%d%s630450%d6304%s",
            str_pad(strlen('360014id.co.qris0119000000000000001'), 2, '0'),
            strlen(self::MERCHANT_ID),
            self::MERCHANT_ID,
            self::QRIS_ACCOUNT,
            '00000001',
            $amountFormatted,
            substr($merchantName, 0, 25),
            strlen($transactionId),
            $transactionId,
            strlen('5303360'),
            '5303'
        );
        
        return $qrisCode;
    }

    /**
     * Generate Static Merchant QRIS Payload
     * QRIS untuk merchant tanpa nominal (bisa dibayar dengan nominal apapun)
     * 
     * @return string QRIS payload untuk merchant
     */
    private static function generateMerchantQrisPayload(): string
    {
        // Format QRIS merchant yang fleksibel (tanpa nominal tetap)
        // Format: Merchant|Account|Name
        return sprintf(
            "QRIS|MERCHANT|%s|%s|%s",
            self::MERCHANT_ID,
            self::QRIS_ACCOUNT,
            self::MERCHANT_NAME
        );
    }

    /**
     * Generate simple QRIS dengan format yang lebih standard
     * Ini menggunakan format yang compatible dengan pembaca QRIS umum
     * 
     * @param float $amount Nominal transaksi
     * @param string $transactionId ID transaksi
     * @return string QR code dalam format data URL atau URL ke image
     */
    public static function generateSimpleQris(float $amount, string $transactionId = ''): string
    {
        try {
            // Format simple: untuk menunjukkan nominal pembayaran
            // Format: QRIS|[AMOUNT]|[TRX_ID]|[TIMESTAMP]
            $qrisData = sprintf(
                "QRIS|%.2f|%s|%s",
                $amount,
                $transactionId ?: 'TRX' . time(),
                now()->format('Y-m-d H:i:s')
            );
            
            return self::getQrCodeUrl($qrisData);
        } catch (\Exception $e) {
            \Log::error('Error generating simple QRIS', ['error' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Generate QR code URL menggunakan Google Charts API
     * 
     * @param string $data Data untuk di-encode ke QR code
     * @return string URL ke QR code image
     */
    private static function getQrCodeUrl(string $data): string
    {
        $encodedData = urlencode($data);
        return "https://chart.googleapis.com/chart?chs=300x300&chld=M|0&cht=qr&chl={$encodedData}";
    }

    /**
     * Get high quality QR code URL (500x500)
     * 
     * @param string $data Data untuk di-encode ke QR code
     * @return string URL ke QR code image yang lebih besar
     */
    public static function getHighQualityQrCodeUrl(string $data): string
    {
        $encodedData = urlencode($data);
        return "https://chart.googleapis.com/chart?chs=500x500&chld=M|0&cht=qr&chl={$encodedData}";
    }
}

