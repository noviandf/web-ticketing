<?php

namespace App\Traits;

trait OneSignalTrait
{
    public function kirimNotifikasiOneSignal($judul, $pesan)
    {
        $content = ["en" => $pesan];
        $headings = ["en" => $judul];

        $fields = [
            'app_id' => env("ONESIGNAL_APP_ID"), 
            'included_segments' => ['Total Subscriptions'],
            'contents' => $content,
            'headings' => $headings,
            'url' => 'https://unhounded-keenan-squirearchical.ngrok-free.dev/dashboard' // Ganti URL Ngrok Kamu
        ];

        $fields = json_encode($fields);

        // --- MASUKKAN REST API KEY DISINI ---
        // Ganti 'OS_v2_...' dengan Key asli dari Dashboard OneSignal > Settings > Keys & IDs
         $restApiKey = env("ONESIGNAL_REST_API_KEY");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $restApiKey // PASTIKAN ADA SPASI SETELAH 'Basic '
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        // dd($response);
        return $response;
    }
}
