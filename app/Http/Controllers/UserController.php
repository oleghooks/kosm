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
        return $user->favorites_items ?? [];
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
