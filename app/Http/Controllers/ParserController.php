<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParserController extends Controller
{

    public function parse(Request $request){
        $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);

        $parse_type = $request->input('type');
        $parse_id = $request->input('id');

        if($parse_type === 'vk'){
            $ch = curl_init('https://api.vk.com/method/wall.get?owner_id='.$parse_id.'&access_token='.env('VK_SECRET').'&count=100&v=5.131');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $html = curl_exec($ch);
            curl_close($ch);
            $html = json_decode($html);
            $html = json_encode($html->response->items);
            return $html;
        }
    }
}
