<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function add(Request $request){
        /**
         *JSON IN
         * {
         *     item_id,
         *     attach_index,
         *     name,
         *     text,
         *     price_in,
         *     price_out,
         *     photos,
         *     provider_id,
         *     count
         * }
         */
        $request->validate([
            'items' => 'required'
        ]);
        $items = json_decode($request->input('items'));
        foreach ($items as $item) {
            $check_item = Store::where('item_id', $item->id)
                                    ->where('attach_index', $item->attach_index)
                                    ->where('user_id', Auth::id())
                                    ->first();
            if($check_item){
                $check_item->name = $item->name;
                $check_item->count = $check_item->count + $item->count;
                $check_item->price_in = $item->price_in;
                $check_item->price_out = $item->price_out;
                $check_item->text = $item->text;
                $check_item->save();
            }
            else {
                Store::create([
                    'item_id' => $item->id,
                    'attach_index' => $item->attach_index,
                    'name' => $item->name,
                    'text' => $item->text,
                    'price_in' => $item->price_in,
                    'price_out' => $item->price_out,
                    'photos' => json_encode($item->photos),
                    'user_id' => Auth::id(),
                    'provider_id' => $item->provider_id,
                    'count' => $item->count
                ]);
            }
        }
    }

    public function list(){
        $list = Store::where('user_id', Auth::id())->where('count', '>', 0)->get();
        return $list;
    }

    public function edit(Request $request){
        $request->validate([
            'item' => 'required'
        ]);
        $item = json_decode($request->input('item'));
        $item_info = Store::find($item->id);
        if($item_info->user_id === Auth::id()){
            $item_info->text = $item->text;
            $item_info->price_in = $item->price_in;
            $item_info->price_out = $item->price_out;
            $item_info->save();
        }
    }
}
