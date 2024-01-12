<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provide;

class ParserController extends Controller
{

    public static function vk(String $method, Array $params){
        $ch = curl_init('https://api.vk.com/method/'.$method.'?'.http_build_query($params).'&access_token='.env('VK_SECRET').'&v=5.131');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
