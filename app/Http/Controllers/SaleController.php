<?php

namespace App\Http\Controllers;

use App\Models\ProvidersItem;
use App\Models\Sale;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function add(Request $request){
        $request->validate([
            'id' => 'required',
            'count' => 'required',
            'price_out' => 'required',
            'source' => 'required'
        ]);

        $item_info = Store::find($request->input('id'));
        if($item_info->user_id === Auth::id()){
            if(Sale::create([
                'store_id' => $request->input('id'),
                'count' => $request->input('count'),
                'source' => $request->input('source'),
                'price_out' => $request->input('price_out'),
                'name' => $request->input('name') ?? "Клиент",
                'phone' => $request->input('phone') ?? "Без телефона",
                'note' => $request->input('note') ?? "",
                'user_id' => Auth::id()
            ])) {
                $item_info->count = $item_info->count - $request->input('count');
                $item_info->save();
            }
        }
    }
}
