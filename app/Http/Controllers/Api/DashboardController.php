<?php

namespace App\Http\Controllers\Api;


// use App\Operator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\ParlacomService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    protected $login;
    protected $parlacom;

    public function __construct(ParlacomService $parlacom)
    {
        $this->parlacom = $parlacom;

    }//end construct

    public function totalLines(Request $request)
    {       
        //$this->login = Auth::user()->login;
        $this->login = $request->login;
                
        $totalPins = collect($this->parlacom->totalPins($this->login, 0));

        
        if ($totalPins->first()['total'] == 0) {
            $totalPins = collect([]);
        }
        
        if ($totalPins->count() > 0) {
            
            $totalPins = $totalPins->map(function($i) {
                $i['label'] = $i['operadora'];
                $i['slug']  = Str::slug($i['operadora']);
                $i['value'] = $i['total'];
                return $i;
            });
        }
                
        $totalPins = $totalPins->sortByDesc('value')->values();
        
        return response()->json(['success' => true, 'data' => $totalPins]);

    }//end methods


    


    
}//end class
