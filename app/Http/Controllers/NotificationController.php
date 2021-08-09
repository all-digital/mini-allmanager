<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSimcards;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Exception;

class NotificationController extends Controller
{
    //
    public function EmailSmartsim(Request $request)
    {
           //$callerid = $request->callerid;

           try{

              Mail::send(new SendSimcards($request));
              return redirect()->back()->with("successEmail","Solicitação Enviada com sucesso, nosso suporte cuidará do resto!");

           }catch(Exception $e){

              return redirect()->back()->with("errorEmail","Ocorreu um erro na Solicitação, tente novamente mais tarde, ou contate nosso suporte !");

           }      
           
              

            //to é para quem vai o email
            // Mail::to($login = Auth::user()->email)->send(new SendSimcards());            

    }//end methods


}//end class


