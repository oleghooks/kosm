<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatBotCOntroller extends Controller
{
    public function uploadImage($hash, $image_url){
        $image_url = urlencode($image_url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://vk.com/share.php?act=url_attachment");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie:'.env('COOKIE_VK'),
            'User-Agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Mobile Safari/537.36',
            'X-Requested-With:XMLHttpRequest'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "hash=".$hash."&allowed_share_buttons=&index=2&url=".$image_url."&to_mail=1&from=message&module=im");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        $query = substr($output, strpos($output, '"_query" value="') + 16, strpos($output, '"', strpos($output, '"_query" value="') + 100) - strpos($output, '"_query" value="') - 16);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://vk.com/share.php?act=url_attachment_done");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie:'.env('COOKIE_VK'),
            'User-Agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Mobile Safari/537.36',
            'X-Requested-With:XMLHttpRequest'));
        //curl_setopt($ch, CURLOPT_POSTFIELDS, "offset=0&_ajax=1");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "_query=".$query);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode(substr($output, strpos($output, 'parent.onUploadDone(') + 20, strpos($output, ')', strpos($output, 'parent.onUploadDone(') + 20) -  strpos($output, 'parent.onUploadDone(') - 20));
        if(isset($output[1]))
            return $output[1];
        else
            var_dump($output);
    }

    public function test(){
        $info = $this->curlPost('https://vk.com/al_im.php?act=a_start', [
            'act' => 'a_start',
            'al' => '1',
            'history' => '1',
            'im_v' => '3',
            'peer' => '424842978'
        ]);
        echo $info;
        $info = json_decode(htmlspecialchars($info));
        var_dump($info);
        //echo $info->payload[1][0]->hash;
        //$img = $this->uploadImage('1708792513_554c2e884711bc3676', 'https://sun9-30.userapi.com/impg/tFjVJKSlyrlUUOlElA-g5YRKoubkLmjAlopCaw/X6PAvaEXyYk.jpg?size=1169x1174&quality=95&sign=1ba52273f56fe7f71497522afbe345f1&type=album');
        //$this->send('1708792513_b7d87c785f8c4d2e18', 'photo:'.$img.':undefined', '250', 367513620);
    }

    public function curlPost($url, $fields){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie:'.env('COOKIE_VK'),
            'User-Agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Mobile Safari/537.36',
            'X-Requested-With:XMLHttpRequest'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
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
}
