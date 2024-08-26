<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserGuest;

class UserGuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama', 'email', 'tgl_lahir', 'no_pendaftaran'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "user_pmb";
            $title2 = "User PMB Online";
            $indexed = $this->indexed;
            return view('admin.admisi.user.index', compact('title','indexed','title2'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'nama',
                3 => 'email',
                4 => 'tgl_lahir',
                5 => 'no_pendaftaran',
            ];

            $search = [];

            $totalData = UserGuest::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $user = UserGuest::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $user = UserGuest::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('tgl_lahir', 'LIKE', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = UserGuest::where('id', 'LIKE', "%{$search}%")
                ->orWhere('nama', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('tgl_lahir', 'LIKE', "%{$search}%")
                ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%")
                ->count();

            }

            $data = [];

            if (!empty($user)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($user as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['nama'] = $row->nama;
                    $nestedData['email'] = $row->email;
                    $nestedData['tgl_lahir'] = date('d-m-Y', strtotime($row->tgl_lahir));
                    $nestedData['no_pendaftaran'] = $row->no_pendaftaran;
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
            if(empty($request->password)){
                $user = UserGuest::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'email' => $request->email,
                        'tgl_lahir' => $request->tgl_lahir,
                        'no_pendaftaran' => $request->no_pendaftaran,
                    ]
                );
            }else{
                $user = UserGuest::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'email' => $request->email,
                        'tgl_lahir' => $request->tgl_lahir,
                        'no_pendaftaran' => $request->no_pendaftaran,
                        'password' => md5($request->password),
                    ]
                );
            }

            return response()->json('Updated');
        } else {
            $user = UserGuest::updateOrCreate(
                ['id' => $id],
                [
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'tgl_lahir' => $request->tgl_lahir,
                    'no_pendaftaran' => $request->no_pendaftaran,
                    'password' => md5($request->password),
                ]
            );
            if ($user) {
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

        $user[0] = UserGuest::where($where)->first();
        $user[1] = date('Y-m-d',strtotime($user[0]->tgl_lahir));

        return response()->json($user);
    }
    public function destroy(string $id)
    {
        //
        $user = UserGuest::where('id', $id)->delete();
    }
    public function change_password(Request $request){
        $id = $request->id;
        $user = UserGuest::find($id);
        $user->password = $request->password;
        $user->save();
        return response()->json($user);
    }
}
