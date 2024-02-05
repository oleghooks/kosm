<?php

use App\Http\Controllers\ParserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvidersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    if(\Illuminate\Support\Facades\Auth::check()) {
        $is_mobile = preg_match('/(android|bb\\d+|meego).+mobile|avantgo|bada\\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge\\|maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $_SERVER['HTTP_USER_AGENT']);
        return view('main', ['is_mobile' => $is_mobile]);
    }
    else
        return redirect('/auth');
});
Route::get('/auth', [\App\Http\Controllers\AuthController::class, 'vk'])->name('login');

Route::middleware('auth')->group(function () {

    Route::controller(ProvidersController::class)->group(function () {
        Route::get('providers.list', 'getList');
        Route::get('providers.info', 'getInfo');
        Route::post('providers.favorite', 'favorite');
        Route::get('providers.group.info', 'getGroupInfo');
        Route::get('providers.group.add', 'addGroup');
        Route::post('providers.group.add.list', 'addGroupsList');
        Route::get('providers.group.vk_list', 'getGroupsUser');
        Route::get('providers.test', 'test');
    });

    Route::controller(\App\Http\Controllers\CartController::class)->group(function () {
        Route::post('cart.update', 'update');
        Route::get('cart.list', 'list');
        Route::post('cart.make', 'make');
        Route::get('cart.items.info', 'items_info');

    });
    Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
        Route::get('user.favorites', 'favorites_items');
        Route::post('user.favorites.update', 'favorites_items_update');
    });
    Route::controller(\App\Http\Controllers\OrdersController::class)->group(function () {
        Route::get('orders.list', 'list');
        Route::get('orders.info', 'info');
        Route::get('orders.make.photos', 'make_photos');

    });


    Route::get('/parser', [ParserController::class, 'parse']);
});
