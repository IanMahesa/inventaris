<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Exception;

class WebcamController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|string',
            ], [
                'image.required' => 'Please capture an image',
            ]);

            $img = $request->image;
            $folderPath = "uploads/";

            // Terima base64 dengan atau tanpa prefix data:image/png;base64,
            if (str_contains($img, ';base64,')) {
                [$metadata, $base64Data] = explode(';base64,', $img);
            } else {
                $base64Data = $img;
            }

            Log::info('Base64 image (first 100 chars): ' . substr($img, 0, 100));

            $imageData = base64_decode($base64Data);

            if ($imageData === false) {
                Log::error('base64_decode gagal. Data base64: ' . substr($base64Data, 0, 100));
                throw new Exception('Format foto tidak valid, silakan ambil ulang.');
            }

            Storage::disk('local')->put('test.png', $imageData);

            $imageName = uniqid('img_') . '.png';
            $imagePath = $folderPath . $imageName;

            Storage::disk('public')->put($imagePath, $imageData);

            $imageUrl = url('storage/' . $imagePath);

            // Paksa QR code menggunakan GD agar tidak tergantung imagick
            config(['qrcode.default' => 'gd']);

            $qrSvg = QrCode::format('png')
                ->size(200)
                ->generate($imageUrl);

            $qrPath = 'qrcodes/' . uniqid('qr_') . '.png';
            Storage::disk('public')->put($qrPath, $qrSvg);
            $qrUrl = url('storage/' . $qrPath);

            Log::info('Sukses simpan gambar dan QR code, mengirim response sukses.');

            return response()->json([
                'message'     => 'Image and QR Code generated successfully.',
                'image_url'   => $imageUrl,
                'qrcode_url'  => $qrUrl,
            ]);
        } catch (Exception $e) {
            Log::error('QR Code Generation Failed: ' . $e->getMessage());
            Log::error('Exception tertangkap: ' . $e->getMessage());

            return response()->json([
                'message' => 'Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
