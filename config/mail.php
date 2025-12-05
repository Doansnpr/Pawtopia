<?php
// config/mail.php
// Pastikan path vendor benar: file ini ada di project_root/config/mail.php
require dirname(__DIR__) . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Gmail;

// Helper encode
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/**
 * Kirim email via Gmail API (menggunakan token.json + credentials.json).
 * @param string $to alamat tujuan
 * @param string $subject subject email
 * @param string $htmlBody isi email HTML
 * @param string|null $from "Nama <email@gmail.com>" (optional)
 * @return mixed Google API response atau throw Exception
 * @throws Exception
 */
function sendEmailNotif($to, $subject, $htmlBody, $from = null) {
    $credPath = __DIR__ . '/google/credentials.json';
    $tokenPath = __DIR__ . '/google/token.json';

    if (!file_exists($credPath)) {
        throw new Exception("credentials.json tidak ditemukan di: {$credPath}");
    }
    if (!file_exists($tokenPath)) {
        throw new Exception("token.json tidak ditemukan di: {$tokenPath}");
    }

    $client = new Client();
    $client->setAuthConfig($credPath);
    $client->addScope(Gmail::GMAIL_SEND);
    $client->setAccessType('offline');

    $accessToken = json_decode(file_get_contents($tokenPath), true);
    if (!$accessToken || !is_array($accessToken)) {
        throw new Exception("Isi token.json tidak valid.");
    }
    $client->setAccessToken($accessToken);

    // Refresh token jika perlu
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            if (isset($newToken['error'])) {
                throw new Exception("Gagal refresh token: " . json_encode($newToken));
            }
            $accessToken = array_merge($accessToken, $newToken);
            file_put_contents($tokenPath, json_encode($accessToken));
            $client->setAccessToken($accessToken);
        } else {
            throw new Exception('Refresh token tidak tersedia, perlu re-authorize di oauth2callback.');
        }
    }

    $gmail = new Gmail($client);

    // Jika from null, gunakan email default Gmail yang sudah terautentikasi
    if (!$from) {
        // GANTI dengan email Gmail yang kamu gunakan untuk setup Gmail API
        $from = "Pawtopia <pawtopia.petshop@gmail.com>";
    }

    // Build MIME message (headers + HTML body)
    $rawMessageString = "From: {$from}\r\n";
    $rawMessageString .= "To: {$to}\r\n";
    $rawMessageString .= "Subject: {$subject}\r\n";
    $rawMessageString .= "MIME-Version: 1.0\r\n";
    $rawMessageString .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
    $rawMessageString .= $htmlBody;

    $encoded = base64UrlEncode($rawMessageString);

    // Gunakan class Google_Service_Gmail_Message (underscore)
    $msg = new \Google_Service_Gmail_Message();
    $msg->setRaw($encoded);

    // Kirimkan
    try {
        $sent = $gmail->users_messages->send('me', $msg);
        return $sent;
    } catch (Exception $e) {
        // log untuk debugging
        error_log("Gmail API send error: " . $e->getMessage());
        throw $e;
    }
}