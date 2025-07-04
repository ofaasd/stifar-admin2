<?php

namespace App\Helpers\HelperSkripsi;

use App\Models\MasterSkripsi;
use Illuminate\Support\Facades\Auth;

class SkripsiHelper
{
    public static function getIdMasterSkripsi()
    {
        $user  = Auth::user();
        $email = $user->email;
        $nim   = explode('@', $email)[0];
        return MasterSkripsi::where('nim', $nim)->value('id');
    }
}
