<?php

namespace App\Http\Controllers;

use App\Services\QrisService;
use Illuminate\Http\Response;

class QrisController extends Controller
{
    /**
     * Display merchant QRIS code
     */
    public function merchantQris()
    {
        $qrisUrl = QrisService::getMerchantQris();
        return view('qris.merchant', ['qrisUrl' => $qrisUrl]);
    }

    /**
     * Get QRIS code as image
     */
    public function getQrisImage()
    {
        $qrisUrl = QrisService::getMerchantQris();
        
        // Get image dari Google Charts API
        $imageData = file_get_contents($qrisUrl);
        
        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Download QRIS code as PNG
     */
    public function downloadQris()
    {
        $qrisUrl = QrisService::getHighQualityQrCodeUrl(
            "QRIS|MERCHANT|" . QrisService::MERCHANT_ID . "|" . QrisService::QRIS_ACCOUNT . "|" . QrisService::MERCHANT_NAME
        );
        
        // Get image dari Google Charts API
        $imageData = file_get_contents($qrisUrl);
        
        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="QRIS-' . QrisService::MERCHANT_NAME . '-' . date('Y-m-d-His') . '.png"',
        ]);
    }

    /**
     * Preview QRIS code
     */
    public function preview()
    {
        return view('qris.preview');
    }
}
