<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['placeholder', 'id', 'parent_title', 'title', 'url', 'permission_name', 'order'];

    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $title = "Menu";
            $indexed = $this->indexed;
            return view('admin.menu.index', compact('title', 'indexed'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'parent_id',
                3 => 'title',
                4 => 'url',
                5 => 'permission_name',
                6 => 'order',
            ];

            $totalData = Menu::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            // $order = $columns[$request->input('order.0.column')];
            // $dir = $request->input('order.0.dir');

            $orderIndex = $request->input('order.0.column', 2); 
            $order = $columns[$orderIndex] ?? 'id';            
            $dir = $request->input('order.0.dir', 'asc');      
            $query = Menu::with('parent');

            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('url', 'LIKE', "%{$search}%");
            }

            $totalFiltered = $query->count();
            $menus = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();

            $data = [];
            $ids = $start;
            foreach ($menus as $row) {
                $nestedData['id'] = $row->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['parent_title'] = $row->parent ? $row->parent->title : '<span class="badge badge-primary">Main Menu</span>';
                $nestedData['title'] = $row->title;
                $nestedData['url'] = $row->url;
                $nestedData['permission_name'] = $row->permission_name;
                $nestedData['order'] = $row->order;
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
        $title = "Tambah Menu";
        // Untuk dropdown parent (Level 1 & 2 saja agar tidak terlalu dalam)
        $parent_menus = Menu::whereNull('parent_id')->orWhereHas('parent', function($q){
            $q->whereNull('parent_id');
        })->get();
        
        $permissions = Permission::orderBy('name')->get();
        
        return view('admin.menu.create', compact('title', 'parent_menus', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'permission_name' => 'required|exists:permissions,name',
            'url'             => 'required',
            'order'           => 'required|integer',
            'parent_id'       => 'nullable|exists:menus,id'
        ]);

        Menu::create($request->all());

        return redirect()->route('menu.index')->with('success', 'Menu baru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $title = "Edit Menu";
        $menu = Menu::findOrFail($id);
        
        // Ambil data parent untuk dropdown (kecuali menu itu sendiri untuk menghindari loop)
        $parent_menus = Menu::where('id', '!=', $id)
                            ->whereNull('parent_id')
                            ->orWhereHas('parent', function($q) use ($id) {
                                $q->whereNull('parent_id')->where('id', '!=', $id);
                            })->get();
                            
        $permissions = \Spatie\Permission\Models\Permission::orderBy('name')->get();

        return view('admin.menu.create', compact('title', 'menu', 'parent_menus', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'title'           => 'required|string|max:255',
            'permission_name' => 'required|exists:permissions,name',
            'url'             => 'required',
            'order'           => 'required|integer',
            'parent_id'       => 'nullable|exists:menus,id|not_in:' . $id // Tidak boleh jadi parent diri sendiri
        ]);

        $menu->update($request->all());

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Jika menu ini adalah parent, anak-anaknya akan ikut terhapus (karena cascade di migration)
        // Atau Anda bisa set null dulu jika ingin menyelamatkan sub-menunya
        $menu->delete();

        return response()->json(['status' => 'success', 'message' => 'Menu berhasil dihapus']);
    }
}
