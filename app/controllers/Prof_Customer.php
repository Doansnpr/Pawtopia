<?php

class Prof_Customer
{
    private $model;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->model = new ProfilCustomer($db);
    }

    public function tampilkanProfil($user_id)
    {
        $profil = $this->model->getCustomerByUserId($user_id);
        $riwayat = [];
        
        if ($profil) {
            $riwayat = $this->model->getRiwayatPemesananByCustomer($profil['id_customer']);
        }

        // Ambil flash message dari session jika ada
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return [
            'profil' => $profil,
            'riwayat' => $riwayat,
            'flash' => $flash
        ];
    }

    public function updateData($user_id, $post)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Hanya kirim data yang ada di tabel users
        $data = [
            'nama_lengkap' => $post['nama_lengkap'],
            'email' => $post['email'],
            'no_hp' => $post['no_hp']
        ];

        if ($this->model->updateCustomer($user_id, $data)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Profil berhasil diperbarui! 😺'];
            $_SESSION['user']['nama_lengkap'] = $post['nama_lengkap']; // Update session nama
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Gagal memperbarui profil.'];
        }
        
        header('Location: ' . BASEURL . '/DashboardCustomer/profil');
        exit;
    }

    public function updatePassword($user_id, $post)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $old = $post['old_password'];
        $new = $post['new_password'];
        $confirm = $post['confirm_password'];

        $currentHash = $this->model->getCurrentPasswordHash($user_id);

        if (!password_verify($old, $currentHash)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Password lama salah! 😿'];
        } elseif ($new !== $confirm) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Konfirmasi password tidak cocok!'];
        } elseif (strlen($new) < 6) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Password minimal 6 karakter.'];
        } else {
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            if ($this->model->updatePassword($user_id, $newHash)) {
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Password berhasil diubah! 😺'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Gagal mengubah password.'];
            }
        }

        header('Location: ' . BASEURL . '/DashboardCustomer/profil');
        exit;
    }
}