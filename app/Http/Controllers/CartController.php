<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ProvidersItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;


/***
 *  CART.JSON info {
 *
 *  }
 *
 * ***/
class CartController extends Controller
{
    public function update(Request $request){
        $cartJson = $request->input('cart');
        $cart = Cart::where('user_id', Auth::id())->first();
        if(!$cart)
            Cart::create([
                'user_id' =>  Auth::id(),
                'cart' => json_encode($cartJson)
            ]);
        else
        {
            $cart->cart = $cartJson;
            $cart->save();
        }
    }
    public function save(Request $request){
        $request->validate([
            'provider_id' => 'required',
            'item_id' => 'required',
            'attach_index' => 'required'
        ]);
        $item_info = ProvidersItem::where('provide_id', $request->input('provider_id'))
            ->where('id', $request->input('item_id'))
            ->first();
        if($item_info){
            $check_cart = Cart::where('item_id', $request->input('item_id'))
                ->where('user_id', Auth::id())
                ->where('attach_index', $request->input('attach_index'))
                ->first();
            if($check_cart){
                $check_cart->count = $request->input('count');
                $check_cart->price = $request->input('price');
                $check_cart->save();
            }
            else
                Cart::create([
                    'item_id' => $request->input('item_id'),
                    'provider_id' => $request->input('provider_id'),
                    'attach_index' => $request->input('attach_index'),
                    'count' => $request->input('count'),
                    'price' => $request->input('price'),
                    'user_id' => Auth::id(),
                ]);
        }
    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $item = Cart::find($request->input('id'));
        if($item->user_id === Auth::id())
                $item->delete();
    }

    public function info(Request $request){
        $request->validate([
            'provider_id' => 'required'
        ]);
        $items = Cart::where('provider_id', $request->input('provider_id'))
            ->where('user_id', Auth::id())
            ->get();
        foreach ($items as &$item) {
            $item->info = ProvidersItem::find($item->item_id);
            $item->info->attachments = json_decode($item->info->attachments);
        }
        return $items;
    }
    public function list(){
        $cart = Cart::where('user_id', Auth::id())->first();
        if(isset($cart->cart))
            return $cart->cart;
        else
            return [];
    }

    public function make(Request $request){
        $request->validate([
            'id' => 'required'
        ]);
        $provider_id = (int)$request->input('id');
        $items = Cart::where('user_id',  Auth::id())->where('provider_id', $provider_id)->get();
        if(count($items) > 0)
            OrdersController::add($items, $provider_id);
        foreach ($items as $item)
            $item->delete();
    }

    public function items_info(){

        $cart = Cart::where('user_id', Auth::id())->first();
        $items = [];
        if(isset($cart->cart)){
            foreach(json_decode($cart->cart) as $item){
                $info_item = ProvidersItem::find($item->item_id);
                $info_item->attachments = json_decode($info_item->attachments);
                $items[] = $info_item;
            }
        }
        return $items;
    }
    public function zip_photo(Request $request){

        //TODO user_id
        $order_id = (int)$request->input('id');
        $order = Order::where('user_id',  Auth::id())->where('id', $order_id)->first();
        if(!$order)
            exit;


        $zip = new ZipArchive();
        $filename = base_path()."/public/tmp/".md5(rand(0,99999999).rand(0,9999999)).".zip";

        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("Невозможно открыть <$filename>\n");
        }
        $cart_items = json_decode($order->items);
        foreach($cart_items as $key => $item){
            $item_info = ProvidersItem::find($item->item_id);
            $attachments = json_decode($item_info->attachments);
            if($attachments[$item->attach_index]->photo){
                $photo = end($attachments[$item->attach_index]->photo->sizes);
                $name = ProvidersController::getTextImage($photo->url, $item->count.' шт.');

                $zip->addFile($name, $key.".jpg");
            }
        }
        $zip->close();
        if (ob_get_contents()) ob_end_clean();

        $file = $filename;

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));

        readfile($file);
        exit();
    }
}
