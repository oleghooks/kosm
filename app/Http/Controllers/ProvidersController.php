<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ParserController;
use App\Models\Provide;
use App\Models\ProvidersItem;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{
    public function getList(){
        $provides = Provide::all();

        return ['response' => $provides];
    }

    public function getInfo(Request $request){
        $request->validate([
            'id' => 'numeric|required'
        ]);
        $id = $request->input('id');

        $provide = Provide::find($id);

        $parse_id = $provide->type_id;
        if($provide->is_group === 1)
            $parse_id = "-".$parse_id;

        if($provide->type === "vk"){
            $response = ParserController::vk('wall.get', [
                'owner_id' => $parse_id,
                'count' => 100,
            ]);
            $response = json_decode($response);
            foreach($response->response->items as &$item){
                if($item->text === "" && isset($item->copy_history)){
                    $item->text = $item->copy_history[0]->text;
                    if(isset($item->copy_history[0]->attachments))
                        $item->attachments = $item->copy_history[0]->attachments;
                }
            }
            return json_encode($response->response->items);
        }
    }

    public function getGroupInfo(Request $request){
        $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);

        $parse_type = $request->input('type');
        $parse_id = $request->input('id');

        if($parse_type === 'vk'){
            return ParserController::vk('groups.getById', [
                'group_id' => $parse_id
            ]);
        }
    }

    public function addGroup(Request $request){
        $info = json_decode($this->getGroupInfo($request));
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

    public function updatePostsGroup($id){

        $provide = Provide::find($id);

        $parse_id = $provide->type_id;
        if($provide->is_group === 1)
            $parse_id = "-".$parse_id;

        if($provide->type === "vk"){
            $response = ParserController::vk('wall.get', [
                'owner_id' => $parse_id,
                'count' => 100,
            ]);
            $response = json_decode($response);
            foreach($response->response->items as &$item){
                if($item->text === "" && isset($item->copy_history)){
                    $item->text = $item->copy_history[0]->text;
                    if(isset($item->copy_history[0]->attachments))
                        $item->attachments = $item->copy_history[0]->attachments;
                }
                //TODO:: Доделать здесь
                $item_info = ProvidersItem::where('provide_id', $id)->where('post_id', $item->id)->first();
                if(!isset($item_info->id)){
                   /* ProvidersItem::create([
                        'provide_id' => $id,

                    ]);*/
                }
            }

        }
    }
}
