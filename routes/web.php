<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

//teste email
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSimcards;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//redirect welcome for newLogin
Route::redirect('/', '/newLogin');
Route::redirect('/login', '/newLogin');
Route::redirect('/register', '/newLogin');
Route::redirect('/simcards/home', '/home');


Route::post('/mail-simcards', 'NotificationController@EmailSmartsim'); 

Route::get('/email', function(){
    // return new \App\Mail\SendSimcards();


    Mail::send(new SendSimcards());
});


Route::get('/session', function () {
    
    // $session = Cache::get('session');
    // // $user = Auth::user()->login;
    // //dd($session);
    // $teste = Auth::user()->login;  
    dd('teste');        
});


//bloqueando essa rotas que foram geradas automaticamente pelo auth
Auth::routes(['register' => false, 'login'=>false]);
// Auth::routes();

Route::get('/simcards/operator','SimCardController@index');



Route::get('/home', 'HomeController@index')->name('home')->middleware('init');

Route::get('/userProfile','HomeController@userProfile');

Route::get('/admin-user','HomeController@adminFormCreateUser')->name('admin-user');
Route::post('/admin-user-create','HomeController@adminCreateUser');

Route::get('/newLogin', 'AuthController@showLogin')->name('newLogin'); 
Route::post('/newLogin/valid', 'AuthController@login')->name('newLoginValid'); 
Route::get('/newLogin/logout', 'AuthController@logout')->name('newLoginLogout'); 


