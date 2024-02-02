<?php

namespace App\Http\Controllers;

use App\Models\ProvidersItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function favorites_items(){
        $user = User::find(Auth::id());
        $items = [];
        $favorites_items = json_decode($user->favorites_items);
        foreach($favorites_items as $item){
            $item_id = $item->item_id;
            $attach_index = $item->attach_index;
            $item_info = ProvidersItem::find($item_id);
            if($item_info){
                $item_info->attach_index = $attach_index;
                $items[] = $item_info;
            }
        }
        return $items;
    }

    public function favorites_items_update(Request $request){
        $request->validate([
            'items' => 'required'
        ]);
        //TODO доделать проверку items
        $user = User::find(Auth::id());
        $user->favorites_items = $request->input('items');
        $user->save();
    }


    public function test(){
        $user = User::find(Auth::id());
        $user->favorites_items = json_encode(array(0 => ['item_id' => 1505, 'attach_index' => 0]));
        $user->save();
        return $this->favorites_items();
    }
}
