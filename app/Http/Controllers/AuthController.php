<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository as Cache;



class AuthController extends Controller
{
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }
    
    public function apiLogin(Request $request)
    {       
        //  $credential = [
        //     'login'=> $request->login,
        //     'password'=>$request->password
        // ];


       // dd( [ 'login' => $request->login,
       //                  'password' => $request->password ]);


        
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->post('http://localhost:8082/api/mini-allmanager/login',[

                        'login' => $request->login,
                        'password' => $request->password 
                    ]);    
                    

        //Se der erro de cliente 400 ou servidor 500, vai retorna true
        if($response->clientError() || $response->serverError())
        {
            return response()->json([
                   'error ' => 'Servidor não responde no momento',
                   'status'=>   $response->status()                                
                ]);

        }else{

            if($response->status() == '200')
            {
                // Cache::put('user',  $response->json(), 40);              
                $this->cache->put('user', $response->json(), 40);
              
                return view('home');

            }else{
                // dd('diferente de 200');
                return response()->json([
                   'error' => 'status diferente de 200',  
                   'status'=>   $response->status()                                    
                ]);
            } 
            // return response()->json($response->json());

        }//end if          
                  

    }//end methods














    ///////////////////////////////
    
    public function dashboard()
    {

        if(Auth::Check() === true){

            return view('teste');
        }
        
        return redirect()->route('newLogin');

    }//end methods


    public function showLogin()
    {
        return view('newLogin');
    }//end methods


    public function login(Request $request)
    {

        $credential = [
            'login'=> $request->login,
            'password'=>$request->password
        ];



        if(Auth::attempt($credential))
        {
             return redirect()->route('home');
            // return view('home');
        }else{
            return redirect()->back()->withInput()->withErrors(['Os dados informados não conferem']);
        }
        
    }//end methods


    public function logout()
    {
        Auth::logout();
        return redirect()->route('newLogin');

    }//end methods


    public function teste(Request $request)
    {

        dd($request->all());

    }//end methods

}//end class
