<?php
namespace App\Support;

use EasyWeChat;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SellerQrcode
{

    public static function url($sellerId)
    {
        $senceId = config('global.seller_prefix') . $sellerId;
        $filePath = 'seller/' . $senceId . '.png';
        $path = public_path($filePath);
        if (file_exists($path)) {
            return asset($filePath);
        }
        $qrcode = EasyWeChat::qrcode();
        $wxQrcode = $qrcode->forever($senceId);
        $result = QrCode::format('png')->size(200)->generate($wxQrcode['url'], public_path($filePath));
        return asset($filePath);
    }
}