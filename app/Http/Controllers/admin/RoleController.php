<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\RoleHasPermission as role_has_permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //
    public $indexed = ['', 'id', 'name', 'guard_name'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Role";
            $indexed = $this->indexed;
            return view('admin.role.index', compact('title','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'name',
                3 => 'guard_name',
            ];

            $search = [];

            $totalData = role::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $role = role::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $role = role::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = role::where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($role)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($role as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['name'] = $row->name;
                    $nestedData['guard_name'] = $row->guard_name;
                    $data[] = $nestedData;
                }
            }
            if ($data) {
                return response()->json([
                  'draw' => intval($request->input('draw')),
                  'recordsTotal' => intval($totalData),
                  'recordsFiltered' => intval($totalFiltered),
                  'code' => 200,
                  'data' => $data,
                ]);
              } else {
                return response()->json([
                  'message' => 'Internal Server Error',
                  'code' => 500,
                  'data' => [],
                ]);
              }
        }
    }
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array', // Mengambil array dari checkbox permissions[]
        ]);

        // 2. Buat Role Baru
        $role = Role::create(['name' => $request->name]);

        // 3. Hubungkan ke Permission
        // syncPermissions akan otomatis mencocokkan nama permission yang dikirim dari form
        $role->syncPermissions($request->permissions);

        return redirect()->route('role.index')->with('success', 'Role dan Permission berhasil dibuat.');
    }
    public function create(){
        $title = "Create Role";
        $allPermissions = Permission::all();
    
        $groupedPermissions = [];
        foreach ($allPermissions as $permission) {
            // Pecah string berdasarkan titik (.)
            $parts = explode('-', $permission->name);
            $group = $parts[0]; // Ini akan jadi Parent
            
            $groupedPermissions[$group][] = $permission;
        } 
        $permissionToMenus = \App\Models\Menu::with('parent')
        ->get()
        ->groupBy('permission_name');
        // $role_has_permission = role_has_permission::all();  
        return view('admin.role.create', compact('title', 'allPermissions','groupedPermissions','permissionToMenus'));
    }
    public function edit(Role $role)
    {
        $title = "Edit Role";
        // Ambil semua permission untuk ditampilkan di pilihan
        $allPermissions = Permission::all();

        // Kelompokkan seperti pada fungsi create agar tampilan tetap konsisten (Parent-Child)
        $groupedPermissions = [];
        foreach ($allPermissions as $permission) {
            $parts = explode('-', $permission->name);
            $group = $parts[0];
            $groupedPermissions[$group][] = $permission;
        }

        // Ambil hanya ID/Nama permission yang sudah dimiliki oleh role ini
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        $permissionToMenus = \App\Models\Menu::with('parent')
        ->get()
        ->groupBy('permission_name');
        

        return view('admin.role.edit', compact('role', 'groupedPermissions', 'rolePermissions', 'title', 'permissionToMenus'));
    }
    public function update(Request $request, Role $role)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        // 2. Update Nama Role
        $role->update(['name' => $request->name]);

        // 3. Sinkronisasi Permission
        // Metode ini sangat praktis karena ia menghapus yang lama dan menyisipkan yang baru secara otomatis
        $role->syncPermissions($request->permissions);

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }
    public function destroy(string $id)
    {
        //
        $role = role::where('id', $id)->delete();
    }
}
