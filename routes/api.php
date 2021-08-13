<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Http;

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

Route::post('/simcards/operator','SimCardController@simcardsAPi');

Route::get('dashboard/total-lines', 'Api\DashboardController@totalLines');



Route::get('teste', function(){

    return response()->json([
    'name' => 'Abigail',
    'state' => 'CA',
    ]);

});

Route::get('/http', function(){

    $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->post('http://localhost:8082/api/mini-allmanager/login',[

                        'login' => '34041035000190',
                        'password' => '123456789'
                    ]); 

                     Cache::put('user', $response->json() , 60);



                     dd(Cache::get('user')['login']);
 

                  


});

Route::get('/user', function(){

    $user = Cache::get('user');

    


    dd(cache('user'));
    dd($user);


});



Route::post('/login', 'AuthController@apiLogin');