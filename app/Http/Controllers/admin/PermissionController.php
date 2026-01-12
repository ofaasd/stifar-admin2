<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    //
    public $indexed = ['', 'id', 'name', 'guard_name'];

    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Permission";
            $indexed = $this->indexed;
            return view('admin.permission.index', compact('title', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'name',
                3 => 'guard_name',
            ];

            $totalData = Permission::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            
            // Pengamanan agar tidak error undefined key 0
            $orderIndex = $request->input('order.0.column', 1);
            $order = $columns[$orderIndex] ?? 'id';
            $dir = $request->input('order.0.dir', 'asc');

            $query = Permission::query();

            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where('name', 'LIKE', "%{$search}%");
            }

            $totalFiltered = $query->count();
            $permissions = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();

            $data = [];
            $ids = $start;
            foreach ($permissions as $row) {
                $nestedData['id'] = $row->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $row->name;
                $nestedData['guard_name'] = $row->guard_name;
                $data[] = $nestedData;
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ]);
        }
    }

    public function create()
    {
        $title = "Tambah Permission";
        return view('admin.permission.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);
        Permission::create(['name' => $request->name, 'guard_name' => $request->guard_name ?? 'web']);
        return redirect()->route('permission.index')->with('success', 'Permission berhasil dibuat');
    }

    public function edit($id)
    {
        $title = "Edit Permission";
        $permission = Permission::findOrFail($id);
        return view('admin.permission.create', compact('title', 'permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:permissions,name,'.$id]);
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name, 'guard_name' => $request->guard_name]);
        return redirect()->route('permission.index')->with('success', 'Permission berhasil diupdate');
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return response()->json(['status' => 'success']);
    }
}
