<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSimcards;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    //
    public function EmailSmartsim(Request $request)
    {
           //$callerid = $request->callerid;
      
            Mail::send(new SendSimcards($request));
              
            return redirect()->back()->withSuccess("Solicitação Enviada com sucesso, nosso suporte cuidará do resto!");

            //to é para quem vai o email
            // Mail::to($login = Auth::user()->email)->send(new SendSimcards());            

    }//end methods


}//end class


