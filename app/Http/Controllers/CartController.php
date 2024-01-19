<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProvidersItem;
use Illuminate\Http\Request;
use ZipArchive;

class CartController extends Controller
{
    public function update(Request $request){
        $request->validate([
            'cart' => 'required'
        ]);

        $cartJson = $request->input('cart');
        //TODO user_id
        $cart = Cart::where('user_id', '1')->first();
        if(!$cart)
            Cart::create([
                'user_id' => '1',
                'cart' => json_encode($cartJson)
            ]);
        else
        {
            $cart->cart = $cartJson;
            $cart->save();
        }
    }

    public function list(){
        //TODO user_id
        $cart = Cart::where('user_id', '1')->first();
        return $cart->cart;
    }

    public function make(Request $request){

        //TODO user_id
        $provider_id = (int)$request->input('id');
        $cart = Cart::where('user_id', 1)->first();
        $zip = new ZipArchive();
        $filename = "../public/tmp/".md5(rand(0,99999999).rand(0,9999999)).".zip";

        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("Невозможно открыть <$filename>\n");
        }
        foreach(json_decode($cart->cart) as $key => $item){
            if($item->provide_id === $provider_id)
            {
                $item_info = ProvidersItem::find($item->item_id);
                $attachments = json_decode($item_info->attachments);
                if($attachments[$item->attach_index]->photo){
                    $photo = end($attachments[$item->attach_index]->photo->sizes);
                    echo $photo->url."<br />";
                    $name = ProvidersController::getTextImage($photo->url, $item->count.' шт.');

                    $zip->addFile($name, $key.".jpg");
                }
            }
        }
        $zip->close();
        ob_end_clean();

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
