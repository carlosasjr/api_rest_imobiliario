<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*Route::get('test', function (Request $request){

    //dd($request->headers->all();
    //dd($request->headers->get('token'));


    $msg = ['msg' =>  'Nenhum produto inserido'];
    //$response =  new \Illuminate\Http\Response(json_encode($msg));
    //$response->header('Content-Type', 'application/json');


    return response()->json($msg);
});*/

Route::namespace('Api')->group(function () {

    Route::prefix('v1')->group(function () {
        Route::post('login', 'Auth\\LoginJwtController@login')->name('login');

        //    Route::resource('products', 'ProductController')->middleware('auth.basic');

        Route::resource('users', 'UserController');

        Route::get('categories/{id}/realState', 'CategoryController@realState');
        Route::resource('categories', 'CategoryController');

        Route::resource('real-states', 'RealStateController');

        Route::prefix('photos')->group(function () {
            Route::delete('/{id}', 'RealStatePhotoController@remove')->name('delete');
            Route::put('set-thumb/{realStateId}/{photoId}', 'RealStatePhotoController@setThumb')->name('setThumb');
        });


    });
});

