<?php


require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeGenerator {
    public static function generatePhoneQRCode ($code, $phoneNumber)
    {
        // Generate QR code with "tel" URI scheme
        $qrCode = new QrCode('tel:' . $phoneNumber);
        $qrCode->setSize(300);

        $image = "generate/" . $code . "_client_phone_call_qr_code.png" ;
        // Set QR code color options
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0]); // Foreground color (black in RGB)
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255]); // Background color (white in RGB)

        // Save QR code as an image
        $writer = new PngWriter();
        $writer->writeFile($qrCode, $image);
    }
}

?>
