<?php 

namespace App;

class helpers
{
    public static function getNilaiHuruf(int $nilai)
    {
        
        if($nilai > 75){
            return 'A';
        }elseif($nilai > 71 && $nilai <= 75){
            return 'AB';
        }elseif($nilai > 65 && $nilai <= 70){
            return 'B';
        }elseif($nilai > 60 && $nilai <= 65){
            return 'BC';
        }elseif($nilai > 55 && $nilai <= 60){
            return 'C';
        }elseif($nilai > 50 && $nilai <= 55){
            return 'CD';
        }else{
            return 'E';
        }
    }
    public static function getKualitas(string $nilai)
    {
        if($nilai == 'A'){
            return 4;
        }elseif($nilai == 'AB'){
            return 3.5;
        }elseif($nilai == 'B'){
            return 3;
        }elseif($nilai == 'BC'){
            return 2.5;
        }elseif($nilai == 'C'){
            return 2;
        }elseif($nilai == 'CD'){
            return 1.5;
        }elseif($nilai == 'D'){
            return 1;
        }else{
            return 0;
        }
    }
}