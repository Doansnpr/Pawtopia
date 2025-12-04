<?php
require_once __DIR__ . '/config/mail.php';

try {
    sendEmailNotif(
        'bintangtharisa@gmail.com',  // ganti dengan email kamu
        'Test Email',
        '<h1>Test Email dari Pawtopia</h1><p>Ini adalah test email.</p>'
    );
    echo "Email berhasil dikirim!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}