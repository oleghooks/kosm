<?php

namespace App\Http\Controllers;

use App\Http\Facades\TimeConverter;
use App\Models\Order;
use App\Models\Provide;
use App\Models\ProvidersItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public static function add($items, $provider_id){
        $order = Order::create([
            'items' => json_encode($items),
            'user_id' => Auth::id(),
            'provider_id' => $provider_id,
            'status' => 0
        ]);
        return $order->id;
    }

    public function list(){
        $orders = Order::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        foreach ($orders as &$order){
            $order->provider_info = Provide::find($order->provider_id);
            $items = json_decode($order->items);
            $items_photos = [];
            $count = 0;
            $summ = 0;
            foreach ($items as $item){
                $count = $count + $item->count;
                $summ = $summ + ($item->count * $item->price);
                $item_info = ProvidersItem::find($item->item_id);
                $item_info->attachments = json_decode($item_info->attachments);
                $items_photos[] = $item_info->attachments[$item->attach_index]->photo->sizes[0]->url;
            }
            $order->summ = $summ;
            $order->count = $count;
            $order->time = TimeConverter::Convert($order->created_at);
            $order->items_photos = $items_photos;
        }
        return $orders;
    }

    public function info(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->input('id');

        $info = Order::where('id', $id)->where('user_id', Auth::id())->first();

        if(!$info)
            exit;

        $info->provider_info = Provide::find($info->provider_id);
        $items = json_decode($info->items);
        $count = 0;
        $summ = 0;
        foreach ($items as &$item){
            $count = $count + $item->count;
            $summ = $summ + ($item->count * $item->price);
            $item_info = ProvidersItem::find($item->item_id);
            $item->info = $item_info;
        }
        $info->summ = $summ;
        $info->count = $count;
        $info->time = TimeConverter::Convert($info->created_at);
        $info->items = $items;
        return $info;
    }
}
