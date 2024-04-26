<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Reset cached roles and permissions
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // // create permissions
        // Permission::create(['name' => 'create pmb']);
        // Permission::create(['name' => 'edit pmb']);
        // Permission::create(['name' => 'update pmb']);
        // Permission::create(['name' => 'delete pmb']);
        // Permission::create(['name' => 'admin dashboard']);
        // Permission::create(['name' => 'pmb dashboard']);

        // // create roles and assign created permissions

        // // or may be done by chaining
        // $role = Role::create(['name' => 'admin-pmb'])
        //     ->givePermissionTo(['create pmb', 'edit pmb','update pmb','delete pmb','pmb dashboard']);

        // $role = Role::create(['name' => 'super-admin']);
        // $role->givePermissionTo(Permission::all());
        // $user = User::all();
        // foreach($user as $row){
        //     if($row->id == 1){
        //         $row->assignRole('super-admin');
        //     }else{
        //         $row->assignRole('admin-pmb');
        //     }
        // }
    }
}
