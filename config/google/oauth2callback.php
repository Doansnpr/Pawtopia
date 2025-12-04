<?php
// Mundur 2 folder ke belakang untuk cari vendor
require '../../vendor/autoload.php'; 

use Google\Client;
use Google\Service\Gmail;

session_start();

$client = new Client();
// Mundur 1 folder dari 'google' ke 'config' untuk cari credentials.json
// Pastikan file credentials.json ada di folder yang sama (config/google/)
$client->setAuthConfig(__DIR__ . '/credentials.json'); 
$client->setAccessType('offline');
$client->setPrompt('select_account consent'); // Penting agar dpt refresh token
$client->addScope(Gmail::GMAIL_SEND);
// Redirect URI harus SAMA PERSIS dengan yang didaftarkan di Google Console
// Biasanya: http://localhost/pawtopia/config/google/oauth2callback.php
$client->setRedirectUri('http://localhost/Pawtopia/config/google/oauth2callback.php');

if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header("Location: $authUrl");
    exit;
}

// Tukar kode jadi token
$client->authenticate($_GET['code']);
$token = $client->getAccessToken();

// Simpan token ke file
file_put_contents('token.json', json_encode($token));

echo "<h3>TOKEN BERHASIL DISIMPAN!</h3>";
echo "<p>Sekarang file token.json sudah terisi. Kamu bisa tutup tab ini.</p>";
echo "<pre>";
print_r($token);
echo "</pre>";
?>