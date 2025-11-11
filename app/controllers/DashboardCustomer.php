<?php

class DashboardCustomer extends Controller {
    public function index() {
        $data = [
            'title' => 'Dashboard Customer',
            'content' => 'dashboard_customer/index',
            'nama_pengguna' => 'Elisa', // ✅ Tambah data ini
            'pengeluaran' => 'Rp 1.000.000',
            'rating' => '4.8',
            'tempat_penitipan' => 'Pawtopia Central',
            'nama_kucing' => 'Miko',
            'tanggal_penitipan' => '5 November 2025',
            'status' => 'Aktif'
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }
}
