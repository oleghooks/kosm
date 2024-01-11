<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provide;

class ParserController extends Controller
{

    public function parse(Request $request){
        $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);

        $parse_type = $request->input('type');
        $parse_id = $request->input('id');

        $provide = Provide::find($parse_id);

        $parse_id = $provide->type_id;
        if($provide->is_group === 1)
            $parse_id = "-".$parse_id;

        if($parse_type === 'vk'){
            $ch = curl_init('https://api.vk.com/method/wall.get?owner_id='.$parse_id.'&access_token='.env('VK_SECRET').'&count=100&v=5.131');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $html = curl_exec($ch);
            curl_close($ch);
            $html = json_decode($html);
            foreach($html->response->items as &$item){
                if($item->text === "" && isset($item->copy_history)){
                    $item->text = $item->copy_history[0]->text;
                    if(isset($item->copy_history[0]->attachments))
                        $item->attachments = $item->copy_history[0]->attachments;
                }
            }
            $html = json_encode($html->response->items);
            return $html;
        }
    }


    public function group_info(Request $request){
        $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);

        $parse_type = $request->input('type');
        $parse_id = $request->input('id');

        if($parse_type === 'vk'){
            $ch = curl_init('https://api.vk.com/method/groups.getById?group_id='.$parse_id.'&access_token='.env('VK_SECRET').'&count=100&v=5.131');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $html = curl_exec($ch);
            curl_close($ch);
            return $html;
        }
    }

    public function add(Request $request){
        $info = json_decode($this->group_info($request));
        $info = $info->response[0];

        if(!Provide::where('type_id', $info->id)->first()){
            $provide = new Provide;
            $provide->name = $info->name;
            $provide->type = 'vk';
            $provide->type_id = $info->id;
            $provide->icon = $info->photo_50;
            $provide->min_summ = 0;
            $provide->last_parser = time();
            $provide->is_group = 1;
            $provide->save();
        }
        else
            $provide = Provide::where('type_id', $info->id)->first();

        return ['id' => $provide->id];
    }

    public function list(){
        $provides = Provide::all();

        return ['response' => $provides];
    }
}
