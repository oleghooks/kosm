<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ProvidersItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

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

    public function list(){
        $cart = Cart::where('user_id', Auth::id())->first();
        if(isset($cart->cart))
            return $cart->cart;
        else
            return [];
    }

    public function make(Request $request){

        $provider_id = (int)$request->input('id');
        $cart = Cart::where('user_id',  Auth::id())->first();
        $items = [];
        $cart_items = json_decode($cart->cart);
        foreach($cart_items as $key => $item){
            if($item->provide_id === $provider_id)
            {
                $items[] = $item;
                unset($cart_items[$key]);
            }
        }
        if(count($items) > 0)
            OrdersController::add($items, $provider_id);
        $cart_items = json_encode($cart_items);
        $cart->cart = $cart_items;
        $cart->save();
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
