<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar permission berdasarkan MenuSeeder sebelumnya
        $permissions = [
            // Dashboard & Master
            'dashboard-view', 'master-data-view', 'master-pt-view', 'pt-profile', 'pt-atribut', 'pt-renstra',
            'master-prodi-view', 'prodi-list', 'prodi-atribut', 'prodi-akreditasi', 'prodi-renstra',
            'master-akademik-view', 'akademik-bidang-minat', 'master-ta', 'master-jabatan', 'master-waktu',
            'master-aset-view', 'aset-kategori', 'aset-vendor', 'aset-jenis-ruang', 'aset-jenis-barang', 
            'aset-lantai', 'aset-jenis-kendaraan', 'aset-merk-kendaraan',

            // Mahasiswa & Berkas
            'mahasiswa-view', 'mahasiswa-data', 'berkas-view', 'berkas-dosen', 'berkas-mahasiswa',

            // Admisi
            'admisi-view', 'admisi-jalur', 'admisi-gelombang', 'admisi-pendaftaran', 'admisi-ujian', 
            'admisi-peringkat', 'admisi-verifikasi', 'admisi-verif-bayar', 'admisi-pengumuman', 
            'admisi-nim-view', 'nim-generate', 'nim-preview', 'admisi-statistik', 'admisi-biaya',

            // Akademik Utama
            'akademik-view', 'akad-matkul', 'akad-jadwal', 'akad-krs-view', 'krs-input', 'krs-statistik', 
            'akad-presensi-view', 'presensi-setting', 'akad-nilai-view', 'nilai-input', 'nilai-kartu-ujian', 
            'akad-khs', 'akad-skripsi-view', 'skripsi-pengajuan', 'skripsi-sidang', 'akad-yudisium-view', 'yudisium-proses',

            // Kepegawaian, Keuangan, Aset
            'kepegawaian-view', 'pegawai-data', 'pegawai-absensi', 'keuangan-view',
            'aset-view', 'aset-ruang', 'aset-kendaraan', 'aset-barang',

            // Setting & Support
            'setting-view', 'setting-user', 'setting-role', 'support-view', 'support-saran', 'support-export'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
