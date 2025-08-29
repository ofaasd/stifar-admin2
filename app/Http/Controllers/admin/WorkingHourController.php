<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\WorkingHour;
use App\Models\User;

class WorkingHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = "Working Hour Management";
        $user = User::all();
        $array_day = [1=>"Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        return view('admin.presence.working.index',compact('title','user','array_day'));
    }
    public function get_table(){

        $no = 0;
        $working = User::all();
        return view('admin.presence.working.table',compact('no','working'));
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
        $user_id = $request->user_id;
        $hasil = 0;
        foreach($request->day as $key=>$day){
            $workin = WorkingHour::where('user_id',$user_id)->where('days',$day);

            if($workin->count() > 0){
                $working_start = 0;
                $working_end = 0;
                if(!empty($request->working_start) && $request->working_start != "00:00" ){
                    $working_start = strtotime($request->working_start[$key]);
                }

                if(!empty($request->working_end) && $request->working_end != "00:00" ){
                    $working_end = strtotime($request->working_end[$key]);
                }
                $working = WorkingHour::find($workin->first()->id);
                $working->working_start = $working_start;
                $working->working_end = $working_end;
                $working->save();
                $hasil++;
            }else{
                $working = WorkingHour::create([
                    'user_id' => $user_id,
                    'days' => $day,
                    'working_start' => strtotime($request->working_start[$key]),
                    'working_end' => strtotime($request->working_end[$key]),
                ]);
                $hasil++;
            }
        }
        if($hasil > 0){
            return response()->json('updated');
        }else{
            return response()->json('error');
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
        $array_day = [1=>"Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        $working = [];

        foreach($array_day as $key=>$day){
            $data = WorkingHour::where('user_id',$id)->where('days',$key)->first();
            $working[$key][] = $data;
            $working[$key]['working_start'] = (!empty($data->working_start))?date('H:i',$data->working_start):"00:00";
            $working[$key]['working_end'] = (!empty($data->working_end))?date('H:i',$data->working_end):"00:00";
        }
        return response()->json($working);
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
        $user = WorkingHour::where('user_id', $id)->delete();
    }
}
