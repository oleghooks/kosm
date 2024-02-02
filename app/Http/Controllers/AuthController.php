<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function vk(Request $request){
        $code = $request->input('code');
        if($code){
            $params = [
                'client_id' => env('VK_CLIENT_ID'),
                'client_secret' => env('VK_CLIENT_SECRET'),
                'redirect_uri' => env('VK_REDIRECT_URI'),
                'code' => $code
            ];
            $ch = curl_init('https://oauth.vk.com/access_token?'.http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            //var_dump($response);
            if(isset($response->access_token)){
                //TODO сделать авторизацию
                $check_user = User::where('vk_id', $response->user_id)->first();
                if($check_user){
                    $check_user->vk_access_token = $response->access_token;
                    $check_user->save();
                    Auth::loginUsingId($check_user->id, $remember = true);
                    return redirect('/');
                }
                else{
                    $user = User::create([
                        'vk_id' => $response->user_id,
                        'vk_access_token' => $response->access_token
                    ]);
                    Auth::loginUsingId($user->id, $remember = true);
                    return redirect('/');
                }
            }
            if(isset($response->error))
                echo "Ошибка авторизации. Причина - ".$response->error_description."<a href='/auth'>Попробуйте снова</a>";

        }
        else
            echo "<a href='https://oauth.vk.com/authorize?client_id=51838883&scope=offline&redirect_uri=".env('VK_REDIRECT_URI')."'>ВК авторизация</a>";
        //TODO доделать кнопку вк
    }
}
