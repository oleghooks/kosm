<?php

namespace App\Http\Controllers;

use App\Http\Facades\TimeConverter;
use App\Models\Provide;
use App\Models\ProvidersItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProvidersController extends Controller
{
    public function getList(){
        $provides = Provide::where('user_id', Auth::id())
            ->orderBy('favorites', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        return ['response' => $provides];
    }

    public function favorite(Request $request){
        $request->validate([
            'id' => 'required'
        ]);
        $id = $request->input('id');

        $provide = Provide::where('id', $id)->where('user_id', Auth::id())->first();
        if($provide){
            if($provide->favorites > 0)
                $provide->favorites = 0;
            else
                $provide->favorites = time();

            $provide->save();
        }
    }

    public function getInfo(Request $request){
        $request->validate([
            'id' => 'numeric|required',
            'order' => 'required'
        ]);
        $limit = 15;
        $offset = ($request->input('p') ?? 0) * $limit;
        $id = $request->input('id');
        $order_by = $request->input('order');
        $provide = Provide::where('user_id', Auth::id())->where('id', $id)->first();
        $items = [];
        if($provide->id > 0){
            if((time() - $provide->last_parser) > 86400)
                $this->updatePostsGroup($id);
            if($order_by == 'popular') {
                $items = ProvidersItem::where('provide_id', $provide->id)
                    ->where('post_date', '>', (time() - 864000))
                    ->orderBy($order_by, 'DESC')
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                if(count($items) === 0)
                    $items = ProvidersItem::where('provide_id', $provide->id)
                        ->orderBy($order_by, 'DESC')
                        ->limit($limit)
                        ->offset($offset)
                        ->get();

            }
            else
                $items = ProvidersItem::where('provide_id', $provide->id)
                ->orderBy($order_by, 'DESC')
                ->limit($limit)
                ->offset($offset)
                ->get();

            foreach ($items as &$item) {
                $item->attachments = json_decode($item->attachments);
                $attachs = [];
                foreach($item->attachments as $attach)
                    if($attach->type === 'photo')
                        $attachs[] = $attach;
                $item->attachments = $attachs;
                $item->post_date = TimeConverter::Convert($item->post_date);
            }
        }
        return [
            'provider' => $provide,
            'items' => $items];
    }

    public function getGroupInfo(Request $request){
        $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);

        $parse_type = $request->input('type');
        $parse_id = $request->input('id');
        $parse_id = str_replace('https://vk.com/', '', $parse_id);

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
            $provide->user_id = Auth::id();
            $provide->save();
            $this->updatePostsGroup($provide->id);
        }
        else
            $provide = Provide::where('type_id', $info->id)->first();
        return ['id' => $provide->id];
    }

    public function test1(){
        echo "11";
    }

    public function test(Request $request){
        $peer_id = 424842978;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://m.vk.com/mail");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie:'.env('COOKIE_VK'),
'User-Agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Mobile Safari/537.36',
'X-Requested-With:XMLHttpRequest'));
        //curl_setopt($ch, CURLOPT_POSTFIELDS, "offset=0&_ajax=1");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "peer_id=".$peer_id."&_ajax=1");
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        //$response = json_decode($output);
        $response = json_decode($output);
        //var_dump($response);
        //echo json_encode($response->data[0]->members);
        if($response->data[0]->msgs)
          echo json_encode($response->data[0]->msgs);
    }


    public function updatePostsGroup($id){

        $provide = Provide::find($id);
        $count_items = ProvidersItem::where('provide_id', $provide->id)->count();
        $parse_id = $provide->type_id;
        if($provide->is_group === 1)
            $parse_id = "-".$parse_id;
        //$provide->last_parser = time();
        //$provide->save();
        $count_vk_items = json_decode(ParserController::vk('wall.get', [
            'owner_id' => $parse_id,
            'count' => 1,
        ]));
        $count_vk_items = $count_vk_items->response->count;
        if($provide->type === "vk"){
            if($count_items > 200 || $count_vk_items < 100)
                $max_count = 1;
            else
                $max_count = 11;
            $items = [];
            for($i = 0; $i < $max_count; $i++) {
                $response = ParserController::vk('wall.get', [
                    'owner_id' => $parse_id,
                    'count' => 100,
                    'offset' => 100 * $i
                ]);
                $response = json_decode($response);
                if($i > 0 && (ceil($response->response->count / 100 - 1) === $i))
                    break;
                foreach ($response->response->items as &$item) {
                    if ($item->text === "" && isset($item->copy_history)) {
                        $item->text = $item->copy_history[0]->text;
                        if (isset($item->copy_history[0]->attachments))
                            $item->attachments = $item->copy_history[0]->attachments;
                    }

                    $popular = ceil(($item->likes->count * 100) + ($item->comments->count * 150) + ($item->reposts->count * 170) + $item->views->count);
                    $items[] = [
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
                    ];
                }
            }

            //var_dump($items);
            if(count($items) > 0)
                ProvidersItem::upsert($items,
                    ['post_id', 'provide_id'],
                    [
                        'attachments',
                        'views',
                        'comments',
                        'likes',
                        'reposts',
                        'popular',
                    ]);

        }
    }

    public function getGroupsUser(){
        //$user = User::find(Auth::id());
        $providers = Provide::where('user_id', Auth::id())->get();
        $groups = ParserController::vk('groups.get', [
            'extended' => 1
        ]);

        $items = json_decode($groups);
        $items = $items->response->items;
        foreach ($providers as $provider) {
            foreach($items as $key => $item){
                if($item->id === $provider->type_id)
                    unset($items[$key]);
            }
        }

        return json_encode($items);

    }

    public static function getTextImage($filename, $text){
        // наше изображение
        $img = ImageCreateFromJPEG($filename);
        // определяем цвет, в RGB
        $color = imagecolorallocate($img, 255, 0, 0);

        // указываем путь к шрифту
        $font = base_path().'/resources/fonts/arial_bolditalicmt.ttf';

        $text = urldecode($text);
        imagefilledrectangle($img, 345, 129, 500, 170, imagecolorallocate($img, 0, 0, 0));
        imagettftext($img, 24, 0, 365, 159, $color, $font, $text);
        // 24 - размер шрифта
        // 0 - угол поворота
        // 365 - смещение по горизонтали
        // 159 - смещение по вертикали
        $name = base_path().'/public/tmp/'.md5(rand(0, 999999).rand(0, 999999).rand(0, 999999)).'.jpg';
        imagejpeg($img, $name, 100);
        return $name;

    }

    public function addGroupsList(Request $request){
        $request->validate([
           'groups' => 'required'
        ]);
        $groups_user = json_decode($this->getGroupsUser());
        $groups_add = $request->input('groups');

        foreach($groups_add as $group){
            foreach($groups_user as $group_user){
                if($group_user->id === $group){
                    if(!Provide::where('type_id', $group)->first()){
                        $provide = new Provide;
                        $provide->name = $group_user->name;
                        $provide->type = 'vk';
                        $provide->type_id = $group_user->id;
                        $provide->icon = $group_user->photo_50;
                        $provide->min_summ = 0;
                        $provide->last_parser = 0;
                        $provide->is_group = 1;
                        $provide->user_id = Auth::id();
                        $provide->save();
                    }
                }
            }
        }
    }


}
