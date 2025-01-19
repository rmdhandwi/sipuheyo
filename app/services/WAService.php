<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WAService
{
  public static function sendMessage($kontak, $pesan)
  {
    // Pastikan nomor dalam format internasional
    if (substr($kontak, 0, 1) === '0') {
      $kontak = '+62' . substr($kontak, 1);
    }

    $data = [
      "userkey" => env('ZIVA_USERKEY', ''),
      "passkey" => env('ZIVA_PASSKEY'),
      "to" => $kontak,
      "message" => $pesan
    ];

    $response = Http::post('https://console.zenziva.net/wareguler/api/sendWA/', $data);
    Log::info('Response from Zenziva:', ['response' => $response->json()]);
    return $response->successful();
  }
}
