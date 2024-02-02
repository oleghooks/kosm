<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Provide;
use Illuminate\Support\Facades\Auth;

class ParserController extends Controller
{

    public static function vk(String $method, Array $params){
        $user = User::find(Auth::id());
        $ch = curl_init('https://api.vk.com/method/'.$method.'?'.http_build_query($params).'&access_token='.$user->vk_access_token.'&v=5.131');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
