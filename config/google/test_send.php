<?php
require_once __DIR__ . '/../mail.php';

try {
    sendEmailNotif(
        "emailkamu@gmail.com",
        "TEST EMAIL",
        "<h3>INI TEST EMAIL DARI PAWTOPIA</h3>"
    );

    echo "EMAIL BERHASIL DIKIRIM";

} catch (Exception $e) {
    echo "GAGAL MENGIRIM EMAIL: " . $e->getMessage();
}
