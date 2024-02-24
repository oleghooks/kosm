<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatBotCOntroller extends Controller
{
    public function test(){
        return ParserController::vk('photos.getUploadServer', [
            'album_id' => '238719122'
        ]);
    }
}
