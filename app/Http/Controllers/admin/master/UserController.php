<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PegawaiBiodatum;
use Spatie\Permission\Models\Role;
use App\Models\ModelHasRole;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'name','email','role'];
    public function index(Request $request)
    {
        //
        $role = Role::all();
        if (empty($request->input('length'))) {
            $title = "user";
            $title2 = "User";
            $indexed = $this->indexed;
            return view('admin.master.user.index', compact('title','indexed','title2','role'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'name',
                3 => 'email',
                4 => 'role',
            ];

            $search = [];

            $totalData = User::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $user = User::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $user = User::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = User::where('id', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->count();
            }

            $data = [];

            if (!empty($user)) {
            // providing a dummy id instead of database ids
                $ids = $start;
                $role_list = [];
                foreach($role as $row){
                    $role_list[$row->id] = $row->name;
                }
                $model_role = [];
                $model_has_role = ModelHasRole::all();
                foreach($model_has_role as $row){
                    $model_role[$row->model_id] = $row->role_id;
                }
                foreach ($user as $row) {
                    $curr_role = ModelHasRole::where('model_id',$row->id);
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['name'] = $row->name ?? "Kosong";
                    $nestedData['role'] =   $role_list[$model_role[$row->id]] ?? "";
                    $nestedData['email'] = $row->email;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $id = $request->id;
        if($id){
            if(empty($request->password)){
                $user  = User::updateOrCreate(
                    ['id' => $id],
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                    ]
                );
            }else{
                $user  = User::updateOrCreate(
                    ['id' => $id],
                    [
                        'password' => Hash::make($request->password),
                    ]
                );
            }
            $user_id = $user->id;

            $role = ModelHasRole::where('model_id',$user_id);
            if($role->count() > 0){
                $new_role = ModelHasRole::where('model_id',$user_id)->update(['role_id'=>$request->role]);
            }else{
                $role = ModelHasRole::create(
                    [
                        'role_id' => $request->role,
                        'model_type' => 'App\Models\User',
                        'model_id' => $user_id,
                    ]
                );
            }
            return response()->json('Updated');
        }else{
            $user  = User::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]
            );
            if ($user) {
                $user_id = $user->id;
                $role = ModelHasRole::create(
                    [
                        'role_id' => $request->role,
                        'model_type' => 'App\Models\User',
                        'model_id' => $user_id,
                    ]
                );
                return response()->json('Created');
            } else {
                return response()->json('Failed Create User');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $where = ['id' => $id];

        $user[0] = User::where($where)->first();
        $user[1] = ModelHasRole::where('model_id',$id)->first();

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::where('id', $id)->delete();
    }
}
