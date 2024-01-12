<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParserController;
use App\Http\Controllers\ProvidersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(ProvidersController::class)->group(function(){
    Route::get('providers.list', 'getList');
    Route::get('providers.info', 'getInfo');
    Route::get('providers.group.info', 'getGroupInfo');
    Route::get('providers.group.add', 'addGroup');
});

Route::get('/parser', [ParserController::class, 'parse']);
