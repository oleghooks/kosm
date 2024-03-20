<?php

namespace App\Http\Controllers;

use App\Models\ChatBot;
use App\Models\PreOrder;
use App\Models\ProvidersItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PreOrdersController extends Controller
{
    public function list(){

        $list_chat_bot = ChatBot::where('user_id', Auth::id())->where('status', '<', '3')->orderBy('id', 'desc')->get();
        foreach ($list_chat_bot as &$item){
            $count = 0;
            $item->item = ProvidersItem::find($item->item_id);
            $item->item->attachments = json_decode($item->item->attachments);
            $item->preorders = PreOrder::where('chat_bot_id', $item->id)->where('status', '<', '2')->get();
            foreach($item->preorders as $preorder)
                $count += $preorder->count;
            $item->count_preorders = $count;

        }
        return $list_chat_bot;
    }

    public function add(Request $request){
        $request->validate([
            'chat_bot_id' => 'required|numeric',
            'name' => 'required',
            'count' => 'required|numeric'
        ]);

        if(PreOrder::create([
            'name' => $request->input('name'),
            'chat_bot_id' => $request->input('chat_bot_id'),
            'count' => $request->input('count'),
            'user_id' => Auth::id(),
            'status' => 1
        ]))
            return[
                'type' => 'success',
                'desc' => 'Заказ успешно добавлен'
            ];
        else
            return[
                'type' => 'error',
                'desc' => 'Ошибка при добавлении заказа'
            ];

    }

    public function delete(Request $request){
        $request->validate([
            'preorder_id' => 'required'
        ]);

        $find = PreOrder::find($request->input('preorder_id'));
        if($find->user_id === Auth::id()){
            $find->delete();
            return[
                'type' => 'success',
                'desc' => 'Успешно удалено'
            ];
        }
        else
            return[
                'type' => 'error',
                'desc' => 'Это не ваш заказ'
            ];
    }
}
