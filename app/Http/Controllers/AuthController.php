<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    
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
    }

    public function login(Request $request)
    {

        $credential = [
            'login'=> $request->login,
            'password'=>$request->password
        ];



        if(Auth::attempt($credential))
        {
            return redirect()->route('home');
        }else{
            return redirect()->back()->withInput()->withErrors(['Os dados informados não conferem']);
        }
        
    }//end methods

    public function logout()
    {
        Auth::logout();
        return redirect()->route('newLogin');

    }

}//end class
