<?php

namespace App\Http\Controllers;

use App\Models\ChatBot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatBotCOntroller extends Controller
{
    public function cookie(){
        $user = User::find(Auth::id());
        return $user->cookie_vk;
    }
    public function uploadImage($hash, $image_url){
        $output = $this->curlPost('https://vk.com/share.php?act=url_attachment',
        [
            'hash' => $hash,
            'allowed_share_buttons' => '',
            'index' => '2',
            'url' => $image_url,
            'to_mail' => '1',
            'from' => 'message',
            'module' => 'im'
        ]);
        $query = substr($output, strpos($output, '"_query" value="') + 16, strpos($output, '"', strpos($output, '"_query" value="') + 100) - strpos($output, '"_query" value="') - 16);


        $output = $this->curlPost('https://vk.com/share.php?act=url_attachment_done',
        [
            '_query' => $query
        ]);
        $output = json_decode(substr($output, strpos($output, 'parent.onUploadDone(') + 20, strpos($output, ')', strpos($output, 'parent.onUploadDone(') + 20) -  strpos($output, 'parent.onUploadDone(') - 20));


        if(isset($output[1]))
            return $output[1];
        else
            return $output;
    }
    public function test(Request $request){
        return $this->hashUpload();
    }
    public function sendToChat(Request $request){
        $text = $request->input('text');
        $item_id = $request->input('item_id');
        $image_url = $request->input('image_url');
        $attach_index = $request->input('attach_index');
        $user = User::find(Auth::id());
        $peer_id = $user->vk_chat_id;
        $info_post = $this->curlPost('https://vk.com/al_im.php?act=a_start', [
            'act' => 'a_start',
            'al' => '1',
            'history' => '1',
            'block' => 'true',
            'im_v' => '3',
            'peer' => $peer_id
        ]);
        $info = json_decode(utf8_encode($info_post));
        if(!isset($info->payload[1][0]->hash)) {
            return [
                'type' => 'error',
                'desc' => 'Не удалось получить хэш. Обновите cookie'
            ];
        }
        else {
            $hash_upload = $this->hashUpload();
            $hash =  $info->payload[1][0]->hash;
            $img = $this->uploadImage($hash_upload, $image_url);
            if($img == "NULL") {
                return [
                    'type' => 'error',
                    'desc' => 'Не удалось загрузить изображение. Обновите cookie'
                ];

            }
            $response = json_decode(utf8_encode($this->send($hash, 'photo:'.$img.':undefined', $text, $peer_id)));
            if(isset($response->payload[1][0]->msg_id)) {
                //Статусы: 0-создано
                //  1 - не получилось отправить
                //  2 - отправлено
                //  3 - скрыто
                ChatBot::create([
                    'item_id' => $item_id,
                    'attach_index' => $attach_index,
                    'text_send' => $text,
                    'status' => '2',
                    'user_id' => Auth::id(),
                    'msg_id' => $response->payload[1][0]->msg_id
                ]);
                return [
                    'type' => 'success',
                    'desc' => 'Сообщение успешно отправлено'
                ];
            }
            else {
                return [
                    'type' => 'error',
                    'desc' => 'Не удалось отправить сообщение. Обновите cookie'
                ];
            }
        }
    }

    public function curlPost($url, $fields){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie:'.$this->cookie(),
            'User-Agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Mobile Safari/537.36',
            'X-Requested-With:XMLHttpRequest'));
        if(env('APP_URL') != 'kosm.ru') {
            curl_setopt($ch, CURLOPT_PROXY, "45.145.169.49:63282");
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "9WhmAEgA:75Y3NVBa");
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function hashUpload(){
        $ch = curl_init("https://vk.com/im");
        curl_setopt($ch, CURLOPT_POST, 0);
        if(env('APP_URL') != 'kosm.ru') {
            curl_setopt($ch, CURLOPT_PROXY, "45.145.169.49:63282");
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "9WhmAEgA:75Y3NVBa");
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'Cache-Control:max-age=0',
            'Cookie:'.$this->cookie(),
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            'Sec-Ch-Ua:"Not A(Brand";v="99", "Google Chrome";v="121", "Chromium";v="121"',
            'Sec-Ch-Ua-Mobile:?0',
            'Sec-Ch-Ua-Platform:"Windows"',
            'Sec-Fetch-Dest:document',
            'Sec-Fetch-Mode:navigate',
            'Sec-Fetch-Site:none',
            'Sec-Fetch-User:?1',
            'Upgrade-Insecure-Requests:1',
            'http-client-ip:127.0.0.1',
            "remote-addr:127.0.0.1",
            "http-x-forwarded-for:127.0.0.1",
            "x-forwarder-for:127.0.0.1"
        ));


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $output = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            $message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$message}";
            curl_close($ch);
        }
        else{
            return substr($output, strpos($output, '"share_timehash":"') + 18, strpos($output, "\"", strpos($output, '"share_timehash":"') + 18) - strpos($output, '"share_timehash":"') - 18);
            curl_close($ch);
        }
    }

    public function send($hash, $media, $msg, $to){
        return $this->curlPost('https://vk.com/al_im.php?act=a_send', [
            'act' => 'a_send',
            'al' => '1',
            'hash' => $hash,
            'im_v' => '3',
            'media' => $media,
            'module' => 'img',
            'msg' => $msg,
            'to' => $to
        ]);
    }

    public function settings(){
        $user = User::find(Auth::id());
        return [
            'cookie' => $user->cookie_vk,
            'chat_id' => $user->vk_chat_id
        ];
    }

    public function settings_update(Request $request){
        $user = User::find(Auth::id());
        $user->vk_chat_id = $request->input('chat_id');
        $user->cookie_vk = $request->input('cookie');
        $user->save();
    }
}
