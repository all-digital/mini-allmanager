<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSimcards;

class NotificationController extends Controller
{
    //
    public function EmailSmartsim(Request $request)
    {
        //to é para quem vai o email
        Mail::send(new SendSimcards());
    }//end methods




}//end class
