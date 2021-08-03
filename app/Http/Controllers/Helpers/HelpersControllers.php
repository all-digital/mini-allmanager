<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpersControllers extends Controller
{
    
    // add 1 year for expiration
    public function dataExpiration($date){
        //04/07/2020
        $dateExplode = explode("/",$date);
        $dateExplode[2] = $dateExplode[2] + 1;

        return  implode("/",$dateExplode);

    }// methods



}//end class
