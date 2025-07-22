<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role as role;

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
        //
        $id = $request->id;

        if ($id) {
            $role = role::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $request->name, 
                    'guard_name' => $request->guard_name, 
                ]
            );

            return response()->json('Updated');
        } else {
            $role = role::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $request->name, 
                    'guard_name' => $request->guard_name, 
                ]
            );
            if ($role) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create Academic');
            }
        }
    }
    public function edit(string $id)
    {
        //
        $where = ['id' => $id];

        $role = role::where($where)->first();

        return response()->json($role);
    }
    public function destroy(string $id)
    {
        //
        $role = role::where('id', $id)->delete();
    }
}
