<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // 1. Buat atau temukan Role Admin
        $adminRole = Role::findOrCreate('super-admin');

        // 2. Ambil semua permission yang ada di tabel permissions
        $allPermissions = Permission::all();

        // 3. Sinkronisasikan semua permission ke Role Admin
        // syncPermissions akan menghapus permission lama dan mengganti dengan yang baru (full access)
        $adminRole->syncPermissions($allPermissions);

        // Opsi tambahan: Jika Anda sudah punya user admin, langsung tempelkan role-nya
        // $user = \App\Models\User::where('email', 'admin@mail.com')->first();
        // if($user) { $user->assignRole($adminRole); }
    }
}
