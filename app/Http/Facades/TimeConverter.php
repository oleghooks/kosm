<?php

namespace App\Http\Facades;

class TimeConverter
{
    public static function Convert(string $time):string
    {
        $time_start_day = time() - strtotime('today');
        $correctTime = time() - $time;
        $return = "";
        if($correctTime <= 3600)
            $return = ceil($correctTime / 60)." мин. назад";
        elseif($correctTime > 3600 && $correctTime < $time_start_day)
            $return = ceil($correctTime / 3600)." час. назад";
        elseif($correctTime > $time_start_day && $correctTime < ($time_start_day + 86400))
            $return = "вчера в ".date('H:m', $time);
        else{
            $return = date("d.m.Y", $time)." в ".date('H:m', $time);
        }
        return $return;
    }

}
