<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum seeding untuk menghindari duplikasi
        Menu::truncate();

        // 1. DASHBOARD
        Menu::create([
            'title' => 'Dashboard',
            'url' => 'dashboard',
            'icon' => 'fa fa-home',
            'permission_name' => 'dashboard-view',
            'order' => 1
        ]);

        // 2. MASTER DATA
        $master = Menu::create([
            'title' => 'Master Data',
            'url' => '#',
            'icon' => 'fa fa-book',
            'permission_name' => 'master-data-view',
            'order' => 2
        ]);

            // Sub Master Data
            $pt = Menu::create([
                'parent_id' => $master->id,
                'title' => 'PT (Perguruan Tinggi)',
                'url' => '#',
                'permission_name' => 'master-pt-view',
                'order' => 1
            ]);
                Menu::create(['parent_id' => $pt->id, 'title' => 'Profile', 'url' => 'admin/masterdata/pt', 'permission_name' => 'pt-profile']);
                Menu::create(['parent_id' => $pt->id, 'title' => 'Atribut', 'url' => 'admin/masterdata/pt/atribut', 'permission_name' => 'pt-atribut']);
                Menu::create(['parent_id' => $pt->id, 'title' => 'Renstra', 'url' => 'admin/masterdata/pt/renstra', 'permission_name' => 'pt-renstra']);

            $prodi = Menu::create([
                'parent_id' => $master->id,
                'title' => 'PRODI',
                'url' => '#',
                'permission_name' => 'master-prodi-view',
                'order' => 2
            ]);
                Menu::create(['parent_id' => $prodi->id, 'title' => 'Daftar Prodi', 'url' => 'admin/masterdata/program-studi', 'permission_name' => 'prodi-list']);
                Menu::create(['parent_id' => $prodi->id, 'title' => 'Atribut Prodi', 'url' => 'admin/masterdata/prodi/atribut/1', 'permission_name' => 'prodi-atribut']);
                Menu::create(['parent_id' => $prodi->id, 'title' => 'Akreditasi', 'url' => 'admin/masterdata/prodi/akreditasi', 'permission_name' => 'prodi-akreditasi']);
                Menu::create(['parent_id' => $prodi->id, 'title' => 'Renstra', 'url' => 'admin/masterdata/prodi/renstra/1', 'permission_name' => 'prodi-renstra']);

            $akadMaster = Menu::create([
                'parent_id' => $master->id,
                'title' => 'Akademik',
                'url' => '#',
                'permission_name' => 'master-akademik-view',
                'order' => 3
            ]);
                Menu::create(['parent_id' => $akadMaster->id, 'title' => 'Bidang Minat', 'url' => 'admin/masterdata/akademik/bidang-minat', 'permission_name' => 'akademik-bidang-minat']);

            Menu::create(['parent_id' => $master->id, 'title' => 'Tahun Ajaran', 'url' => 'admin/masterdata/ta', 'permission_name' => 'master-ta', 'order' => 4]);
            Menu::create(['parent_id' => $master->id, 'title' => 'Jabatan', 'url' => 'admin/masterdata/jabatan_struktural', 'permission_name' => 'master-jabatan', 'order' => 5]);
            Menu::create(['parent_id' => $master->id, 'title' => 'Waktu', 'url' => 'admin/masterdata/waktu', 'permission_name' => 'master-waktu', 'order' => 6]);

            $asetMaster = Menu::create([
                'parent_id' => $master->id,
                'title' => 'Aset',
                'url' => '#',
                'permission_name' => 'master-aset-view',
                'order' => 7
            ]);
                Menu::create(['parent_id' => $asetMaster->id, 'title' => 'Kategori', 'url' => 'admin/masterdata/aset/kategori-aset', 'permission_name' => 'aset-kategori']);
                Menu::create(['parent_id' => $asetMaster->id, 'title' => 'Vendor', 'url' => 'admin/masterdata/aset/vendor', 'permission_name' => 'aset-vendor']);
                Menu::create(['parent_id' => $asetMaster->id, 'title' => 'Jenis Ruang', 'url' => 'admin/masterdata/aset/jenis-ruang', 'permission_name' => 'aset-jenis-ruang']);
                Menu::create(['parent_id' => $asetMaster->id, 'title' => 'Jenis Barang', 'url' => 'admin/masterdata/aset/jenis-barang', 'permission_name' => 'aset-jenis-barang']);
                Menu::create(['parent_id' => $asetMaster->id, 'title' => 'Lantai', 'url' => 'admin/masterdata/aset/lantai', 'permission_name' => 'aset-lantai']);
                Menu::create(['parent_id' => $asetMaster->id, 'title' => 'Jenis Kendaraan', 'url' => 'admin/masterdata/aset/jenis-kendaraan', 'permission_name' => 'aset-jenis-kendaraan']);
                Menu::create(['parent_id' => $asetMaster->id, 'title' => 'Merk Kendaraan', 'url' => 'admin/masterdata/aset/merk-kendaraan', 'permission_name' => 'aset-merk-kendaraan']);

        // 3. MAHASISWA
        $mhs = Menu::create(['title' => 'Mahasiswa', 'url' => '#', 'icon' => 'fa fa-users', 'permission_name' => 'mahasiswa-view', 'order' => 3]);
            Menu::create(['parent_id' => $mhs->id, 'title' => 'Data Mahasiswa', 'url' => 'mahasiswa', 'permission_name' => 'mahasiswa-data']);

        // 4. BERKAS
        $berkas = Menu::create(['title' => 'Berkas', 'url' => '#', 'icon' => 'fa fa-file', 'permission_name' => 'berkas-view', 'order' => 4]);
            Menu::create(['parent_id' => $berkas->id, 'title' => 'Dosen', 'url' => 'admin/berkas/dosen', 'permission_name' => 'berkas-dosen']);
            Menu::create(['parent_id' => $berkas->id, 'title' => 'Mahasiswa', 'url' => 'admin/berkas/mahasiswa', 'permission_name' => 'berkas-mahasiswa']);

        // 5. ADMISI
        $admisi = Menu::create(['title' => 'Admisi', 'url' => '#', 'icon' => 'fa fa-file-text', 'permission_name' => 'admisi-view', 'order' => 5]);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Jalur Pendaftaran', 'url' => 'admin/admisi/jalur_pendaftaran', 'permission_name' => 'admisi-jalur']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Gelombang', 'url' => 'admin/admisi/gelombang', 'permission_name' => 'admisi-gelombang']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Pendaftaran Maba', 'url' => 'admin/admisi/peserta', 'permission_name' => 'admisi-pendaftaran']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Pengaturan Ujian PMB', 'url' => 'admin/admisi/daftar_soal', 'permission_name' => 'admisi-ujian']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Peringkat PMDP', 'url' => 'admin/admisi/peringkat', 'permission_name' => 'admisi-peringkat']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Verifikasi Pendaftaran', 'url' => 'admin/admisi/verifikasi', 'permission_name' => 'admisi-verifikasi']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Verifikasi Pembayaran', 'url' => 'admin/admisi/verifikasi/pembayaran', 'permission_name' => 'admisi-verif-bayar']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Pengumuman Peserta', 'url' => 'admin/admisi/pengumuman', 'permission_name' => 'admisi-pengumuman']);
            
            $nimGen = Menu::create(['parent_id' => $admisi->id, 'title' => 'NIM Generator', 'url' => '#', 'permission_name' => 'admisi-nim-view']);
                Menu::create(['parent_id' => $nimGen->id, 'title' => 'Calon Mahasiswa', 'url' => 'admin/admisi/generate_nim', 'permission_name' => 'nim-generate']);
                Menu::create(['parent_id' => $nimGen->id, 'title' => 'Preview', 'url' => 'admin/admisi/generate_nim/preview', 'permission_name' => 'nim-preview']);

            Menu::create(['parent_id' => $admisi->id, 'title' => 'Statistik', 'url' => 'admin/admisi/statistik', 'permission_name' => 'admisi-statistik']);
            Menu::create(['parent_id' => $admisi->id, 'title' => 'Biaya Pendaftaran', 'url' => 'admin/admisi/biaya_pendaftaran', 'permission_name' => 'admisi-biaya']);

        // 6. AKADEMIK (UTAMA)
        $akad = Menu::create(['title' => 'Akademik', 'url' => '#', 'icon' => 'fa fa-university', 'permission_name' => 'akademik-view', 'order' => 6]);
            Menu::create(['parent_id' => $akad->id, 'title' => 'Matakuliah', 'url' => 'admin/masterdata/matakuliah', 'permission_name' => 'akad-matkul']);
            Menu::create(['parent_id' => $akad->id, 'title' => 'Jadwal', 'url' => 'admin/masterdata/jadwal', 'permission_name' => 'akad-jadwal']);
            
            $krs = Menu::create(['parent_id' => $akad->id, 'title' => 'KRS', 'url' => '#', 'permission_name' => 'akad-krs-view']);
                Menu::create(['parent_id' => $krs->id, 'title' => 'Input KRS', 'url' => 'admin/masterdata/krs', 'permission_name' => 'krs-input']);
                Menu::create(['parent_id' => $krs->id, 'title' => 'Statistik KRS', 'url' => 'dashboard_akademik', 'permission_name' => 'krs-statistik']);

            $presensi = Menu::create(['parent_id' => $akad->id, 'title' => 'Presensi', 'url' => '#', 'permission_name' => 'akad-presensi-view']);
                Menu::create(['parent_id' => $presensi->id, 'title' => 'Setting Pertemuan', 'url' => 'admin/akademik/setting-pertemuan', 'permission_name' => 'presensi-setting']);

            $nilai = Menu::create(['parent_id' => $akad->id, 'title' => 'Nilai', 'url' => '#', 'permission_name' => 'akad-nilai-view']);
                Menu::create(['parent_id' => $nilai->id, 'title' => 'Input Nilai', 'url' => 'admin/akademik/nilai', 'permission_name' => 'nilai-input']);
                Menu::create(['parent_id' => $nilai->id, 'title' => 'Kartu Ujian', 'url' => 'admin/akademik/ujian', 'permission_name' => 'nilai-kartu-ujian']);

            Menu::create(['parent_id' => $akad->id, 'title' => 'KHS', 'url' => 'admin/akademik/khs', 'permission_name' => 'akad-khs']);
            
            $skripsi = Menu::create(['parent_id' => $akad->id, 'title' => 'Skripsi', 'url' => '#', 'permission_name' => 'akad-skripsi-view']);
                Menu::create(['parent_id' => $skripsi->id, 'title' => 'Pengajuan', 'url' => 'admin/akademik/skripsi/pengajuan', 'permission_name' => 'skripsi-pengajuan']);
                Menu::create(['parent_id' => $skripsi->id, 'title' => 'Jadwal Sidang', 'url' => 'sidang', 'permission_name' => 'skripsi-sidang']);

            $yudisium = Menu::create(['parent_id' => $akad->id, 'title' => 'Yudisium', 'url' => '#', 'permission_name' => 'akad-yudisium-view']);
                Menu::create(['parent_id' => $yudisium->id, 'title' => 'Proses', 'url' => 'admin/akademik/yudisium/proses', 'permission_name' => 'yudisium-proses']);

        // 7. KEPEGAWAIAN
        $pegawai = Menu::create(['title' => 'Kepegawaian', 'url' => '#', 'icon' => 'fa fa-users', 'permission_name' => 'kepegawaian-view', 'order' => 7]);
            Menu::create(['parent_id' => $pegawai->id, 'title' => 'Data Pegawai', 'url' => 'admin/kepegawaian/pegawai', 'permission_name' => 'pegawai-data']);
            Menu::create(['parent_id' => $pegawai->id, 'title' => 'Absensi', 'url' => 'attendance/report', 'permission_name' => 'pegawai-absensi']);

        // 8. KEUANGAN (Placeholder dari include Anda)
        Menu::create(['title' => 'Keuangan', 'url' => 'admin/keuangan', 'icon' => 'fa fa-money', 'permission_name' => 'keuangan-view', 'order' => 8]);

        // 9. ASET
        $aset = Menu::create(['title' => 'Aset', 'url' => '#', 'icon' => 'fa fa-support', 'permission_name' => 'aset-view', 'order' => 9]);
            Menu::create(['parent_id' => $aset->id, 'title' => 'Ruang', 'url' => 'admin/masterdata/ruang', 'permission_name' => 'aset-ruang']);
            Menu::create(['parent_id' => $aset->id, 'title' => 'Kendaraan', 'url' => 'admin/aset/kendaraan', 'permission_name' => 'aset-kendaraan']);
            Menu::create(['parent_id' => $aset->id, 'title' => 'Barang', 'url' => 'admin/aset/barang', 'permission_name' => 'aset-barang']);

        // 10. SETTING
        $setting = Menu::create(['title' => 'Setting', 'url' => '#', 'icon' => 'fa fa-support', 'permission_name' => 'setting-view', 'order' => 10]);
            Menu::create(['parent_id' => $setting->id, 'title' => 'Pengaturan Pengguna', 'url' => 'admin/masterdata/user', 'permission_name' => 'setting-user']);
            Menu::create(['parent_id' => $setting->id, 'title' => 'Role Pengguna', 'url' => 'admin/role', 'permission_name' => 'setting-role']);

        // 11. SUPPORT
        $support = Menu::create(['title' => 'Support', 'url' => '#', 'icon' => 'fa fa-support', 'permission_name' => 'support-view', 'order' => 11]);
            Menu::create(['parent_id' => $support->id, 'title' => 'Kritik dan Saran', 'url' => 'support/saran', 'permission_name' => 'support-saran']);
            Menu::create(['parent_id' => $support->id, 'title' => 'Export Data', 'url' => 'support/export', 'permission_name' => 'support-export']);
    }
}
