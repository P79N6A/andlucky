<?php
namespace App\Support;

use EasyWeChat;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DoctorQrcode
{

    public static function url($doctorId)
    {
        $senceId = config('global.doctor_prefix') . $doctorId;
        $filePath = 'doctor/' . $senceId . '.png';
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