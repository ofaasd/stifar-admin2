<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DateInterval;
use DatePeriod;

class PresenceController extends Controller
{
    //
    public $indexed = ['', 'id', 'user_id', 'day', 'masuk', 'keluar'];
    public function index(Request $request)
    {
        //
        $role = Auth::user()->getRoleNames();
        $user = User::all();
        $date_start = date('Y-m-1');
        $date_end = date('Y-m-d');
        if(!empty($request->date_start) && !empty($request->date_end)){
            $date_start = $request->date_start;
            $date_end = $request->date_end;
        }
        $role = $role[0];
        $tanggal = date('Y-m-d');
        //$tanggal = "2024-09-20";
        $absensi = Presence::where('user_id', Auth::user()->id)
            //->where('day', date('Y-m-d'))
            ->where('day', $tanggal)
            ->first();

        $list_absensi = Presence::where('user_id', Auth::user()->id)
            ->where('day', '>=', $date_start)
            ->where('day', '<=', $date_end)
            ->get();

        $title = 'Absensi';
        $indexed = $this->indexed;

        return view('dosen.presence.index', compact('title', 'indexed', 'absensi', 'list_absensi','tanggal','date_start','date_end'));


    }

    public function store(Request $request)
    {
        //
        $role = Auth::user()->getRoleNames();
        $role = $role[0];
        $get_day = date('w');
        $curr_day = $get_day+1;
        $working = WorkingHour::where('user_id',Auth::user()->id)->where('days',$curr_day)->first();
        $get_hour = strtotime(date("H:i:s"));
        //$get_hour = strtotime("16:12:10");
        if($working->working_start != 0 && $working->working_end != 0){

            // echo date("H:i:s",$working->working_start) . "<br/>";
            // echo date("H:i:s") . "<br/>";
            $working_start = strtotime(date("H:i:s",$working->working_start));
            $diff = (int)$working_start - (int)$get_hour;
            $diff_hour = gmdate("H:i:s", $diff);
            if($working_start >= $get_hour){
                $ts_late = 0;
            }else{
                $sec = (abs($diff)%60);
                $min = (floor(abs($diff)/60)%60);
                $hour = floor(abs($diff)/3600);
                if($hour < 10){
                    $hour = "0" . $hour;
                }
                if($min < 10){
                    $min = "0" . $min;
                }
                if($sec < 10){
                    $sec = "0" . $sec;
                }
                $late = $hour . ":" . $min . ":" . $sec;
                $ts_late = strtotime($late);
                //echo "Your Late : " . $late . " timestamp " . strtotime($late) . " ";
            }
            $overtime = 0;
        }else{
            $ts_late = 0;
            $overtime = 1;
        }
        $img = $request->image;
        $folderPath = 'img/upload/absensi/';
        $absensi = Presence::where('user_id', Auth::user()->id)
            //->where('day', date('Y-m-d'))
            ->where('day',$request->tanggal)
            ->first();
        $image_parts = explode(';base64,', $img);
        $image_type_aux = explode('image/', $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.' . $image_type;

        $file = $folderPath . $fileName;
        if (file_put_contents($file, $image_base64)) {
            if (empty($absensi->start)) {
                $absensi = new Presence();
                $absensi->user_id = Auth::user()->id;
                $absensi->is_remote = 1;
                $absensi->day = $request->tanggal;
                $absensi->start = $get_hour;
                $absensi->lat_start = $request->lat;
                $absensi->long_start = $request->long;
                $absensi->ip_start = $_SERVER['REMOTE_ADDR'];
                $absensi->browser_start = $_SERVER['HTTP_USER_AGENT'];
                $absensi->image_start = $fileName;
                $absensi->start_late = $ts_late;
                $absensi->overtime = $overtime;
            } elseif (empty($absensi->end)) {
                $absensi = Presence::find($absensi->id);
                $absensi->end = $get_hour;
                $absensi->lat_end = $request->lat;
                $absensi->long_end = $request->long;
                $absensi->ip_end = $_SERVER['REMOTE_ADDR'];
                $absensi->browser_end = $_SERVER['HTTP_USER_AGENT'];
                $absensi->image_end = $fileName;
            } else {
                return response()->json('Data Sudah dimasukan');
            }
            //$absensi->save();
            // Storage::put($file, $image_base64);
            // Storage::move($file, public_path('assets/img/upload/absensi/' . $fileName));
            //file_put_contents(public_path() . '/assets/img/upload/absensi/' . $filename, $image_base64);
            if ($absensi->save()) {
            return response()->json('Data berhasil Di Input');
            } else {
            return response()->json('Data Gagal Disimpan');
            }
        }else{
            return response()->json('Foto tida ditemukan');
        }
        //echo $diff_hour;
    }
    public function report(Request $request){
        $title = "Report Attendance";
        $date_start = date('Y-m-01');
        $date_end = date('Y-m-d');
        if(!empty($request->date_start) && !empty($request->date_end)){
            $date_start = $request->date_start;
            $date_end = $request->date_end;
        }
        $begin = new DateTime($date_start);
        $end = new DateTime($date_end);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $user = User::all();
        $total_on = [];
        $total_late = [];
        $total_not = [];
        foreach($period as $dt){
            $tanggal = $dt->format('Y-m-d');
            $tanggal_now = date('Y-m-d');

            foreach($user as $row){
                $total_on[$tanggal][$row->id] = 0;
                $total_late[$tanggal][$row->id] = 0;
                $total_not[$tanggal][$row->id] = 0;
                if($tanggal <= $tanggal_now){
                    $presence = Presence::select('presences.*','users.name')->join('users','users.id','=','user_id')->where('day',$tanggal)->where('user_id',$row->id)->first() ?? '';
                    if(!empty($presence)){
                        if($presence->start_late == 0){
                            $total_on[$tanggal][$row->id] += 1;
                        }else{
                            $total_late[$tanggal][$row->id] += 1;
                        }
                    }else{
                        $convert_hari = date('w',strtotime($tanggal)) + 1;
                        $working = WorkingHour::where('user_id',$row->id)->where('day',$convert_hari)->where('working_start','<>',0)->count();
                        if($working > 0){
                            // echo $convert_hari . " ";
                            // echo $row->id . " " . $working . "<br />";
                            $total_not[$tanggal][$row->id] += 1;
                        }
                    }
                }
            }
        }
        $no =0;
        $jumlah = $total_on;
        $status = 1;
        if(!empty($request->status)){
            $status = $request->status;
            if($status == 1){
                $jumlah = $total_on;
            }elseif($status == 2 ){
                $jumlah = $total_late;
            }else{
                $jumlah = $total_not;
            }
        }
        // exit;
        $list_status = array(1=>'On Time','Late','Not Absence');

        return view('admin.presence.report',compact('user','title','period','no','jumlah','list_status','status','date_start','date_end'));
    }
    public function log(Request $request){
        $title = "Report Log";
        $date_start = date('Y-m-01');
        $date_end = date('Y-m-d');
        if(!empty($request->date_start) && !empty($request->date_end)){
            $date_start = $request->date_start;
            $date_end = $request->date_end;
            //echo "masuk sini";
            echo $date_start;
        }
        $list_absensi = Presence::select('presences.*','users.name')
            ->join('users','users.id','=','presences.user_id')
            ->where('day', '>=', $date_start)
            ->where('day', '<=', $date_end)
            ->get();

        return view('admin.presence.log', compact('title', 'list_absensi','date_start','date_end'));
    }
}
