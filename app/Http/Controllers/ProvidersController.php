<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ParserController;
use App\Http\Facades\TimeConverter;
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
            'id' => 'numeric|required',
            'order' => 'required'
        ]);
        $id = $request->input('id');
        $order_by = $request->input('order');
        $provide = Provide::find($id);
        $items_response = [];
        if($provide->id > 0){
            $items = ProvidersItem::where('provide_id', $provide->id)
                ->orderBy($order_by, 'DESC')
                ->get();
            if(count($items) === 0 or (time() - $provide->last_parser) > 86400){
                $this->updatePostsGroup($id);
                $items = ProvidersItem::where('provide_id', $provide->id)
                    ->orderBy($order_by, 'DESC')
                    ->get();
            }
            foreach ($items as &$item) {
                $item->attachments = json_decode($item->attachments);
                $item->post_date = TimeConverter::Convert($item->post_date);
            }
            $items_response = $items;
        }
        return [
            'provider' => $provide,
            'items' => $items_response];
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
            $this->updatePostsGroup($provide->id);
        }
        else
            $provide = Provide::where('type_id', $info->id)->first();
        return ['id' => $provide->id];
    }

    public function test(Request $request){
        $this->getTextImage("https://sun9-44.userapi.com/impg/rCUKlqxv9qrtVNX7ckKss8zFh0cilA-PPEcWnQ/A0Yo6SZtnCk.jpg?size=510x643&quality=95&sign=26ca6650987bda4aceff502495f58352&c_uniq_tag=1FY7r3Oird6rZYqLztBzPUMtCGuNUgyeHqTh0ylQorU&type=album", "1 шт.");
    }
    public function updatePostsGroup($id){

        $provide = Provide::find($id);

        $parse_id = $provide->type_id;
        if($provide->is_group === 1)
            $parse_id = "-".$parse_id;
        $provide->last_parser = time();
        $provide->save();
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
                if($item->reposts->count === 0)
                    $item->reposts->count = 1;
                if($item->comments->count === 0)
                    $item->comments->count = 1;
                if($item->likes->count === 0)
                    $item->likes->count = 1;
                $popular = ceil($item->likes->count * $item->comments->count * $item->reposts->count * $item->views->count * $item->date / 1000000000);
                //echo $popular."<br />";
                //TODO:: Доделать здесь
                $item_info = ProvidersItem::where('provide_id', $id)->where('post_id', $item->id)->first();
                if(!isset($item_info->id)){
                    ProvidersItem::create([
                        'provide_id' => $id,
                        'text' => $item->text,
                        'price' => 0,
                        'attachments' => json_encode($item->attachments),
                        'views' => $item->views->count,
                        'comments' => $item->comments->count,
                        'likes' => $item->likes->count,
                        'reposts' => $item->reposts->count,
                        'popular' => $popular,
                        'post_id' => $item->id,
                        'post_date' => $item->date,
                    ]);

                }
            }

        }
    }

    public static function getTextImage($filename, $text){
        // наше изображение
        $img = ImageCreateFromJPEG($filename);
        var_dump(__DIR__);
        // определяем цвет, в RGB
        $color = imagecolorallocate($img, 255, 0, 0);

        // указываем путь к шрифту
        $font = '..\resources\fonts\arial_bolditalicmt.ttf';

        $text = urldecode($text);
        imagefilledrectangle($img, 345, 129, 500, 170, imagecolorallocate($img, 0, 0, 0));
        imagettftext($img, 24, 0, 365, 159, $color, $font, $text);
        // 24 - размер шрифта
        // 0 - угол поворота
        // 365 - смещение по горизонтали
        // 159 - смещение по вертикали
        $name = '../public/tmp/'.md5(rand(0, 999999).rand(0, 999999).rand(0, 999999)).'.jpg';
        imagejpeg($img, $name, 100);
        return $name;

    }
}
