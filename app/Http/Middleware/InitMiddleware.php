<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Extensions\ParlacomService;
use Illuminate\Support\Facades\Auth;

class InitMiddleware
{
    public $parlacom;

    public function __construct(ParlacomService $parlacom)
    {        
        $this->parlacom = $parlacom;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {                
        // $login = Auth::user()->login;
        // Cache::put('login', $login, 60);

        return $next($request);
    }
}
