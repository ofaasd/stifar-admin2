<?php

namespace App\Http\Controllers\admin\admisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterSlide;

class SlideController extends Controller
{
    //
    public $indexed = ['', 'id', 'gambar', 'caption', 'link'];
    public function index(Request $request)
    {
        //
        if (empty($request->input('length'))) {
            $title = "Slideshow";
            $indexed = $this->indexed;
            return view('admin.admisi.slide.index', compact('title','indexed'));
        }else{
            $columns = [
                1 => 'id',
                2 => 'gambar',
                3 => 'caption',
                4 => 'link',
            ];

            $search = [];

            $totalData = MasterSlide::count();

            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');


            if (empty($request->input('search.value'))) {
                $slide = MasterSlide::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $search = $request->input('search.value');

                $slide = MasterSlide::where('id', 'LIKE', "%{$search}%")
                    ->orWhere('gambar', 'LIKE', "%{$search}%")
                    ->orWhere('caption', 'LIKE', "%{$search}%")
                    ->orWhere('link', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = MasterSlide::where('id', 'LIKE', "%{$search}%")
                ->orWhere('gambar', 'LIKE', "%{$search}%")
                ->orWhere('caption', 'LIKE', "%{$search}%")
                ->orWhere('link', 'LIKE', "%{$search}%")
                ->count();

            }

            $data = [];

            if (!empty($slide)) {
            // providing a dummy id instead of database ids
                $ids = $start;

                foreach ($slide as $row) {
                    $nestedData['id'] = $row->id;
                    $nestedData['fake_id'] = ++$ids;
                    $nestedData['gambar'] = $row->nama;
                    $nestedData['caption'] = $row->email;
                    $nestedData['link'] = date('d-m-Y', strtotime($row->tgl_lahir));
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

            $slide = MasterSlide::updateOrCreate(
                ['id' => $id],
                [
                    'gambar' => $request->nama,
                    'caption' => $request->email,
                    'link' => $request->tgl_lahir,
                ]
            );

            return response()->json('Updated');
        } else {
            $slide = MasterSlide::updateOrCreate(
                ['id' => $id],
                [
                    'gambar' => $request->nama,
                    'caption' => $request->email,
                    'link' => $request->tgl_lahir,
                ]
            );
            if ($slide) {
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

        $slide[0] = MasterSlide::where($where)->first();

        return response()->json($slide);
    }
    public function destroy(string $id)
    {
        //
        $slide = MasterSlide::where('id', $id)->delete();
    }
}
