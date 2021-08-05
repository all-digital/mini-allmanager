<?php

namespace App\Http\Controllers;

use App\User;
use App\SimCard;
use App\Operator;
use App\SimcardReport;
use Illuminate\Http\Request;
use App\Exports\SimcardExport;
use App\Exports\NeverConnect;
use App\Exports\ExportUniqueReport;
use App\Extensions\ParlacomService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Cache\Repository as Cache;

use Illuminate\Support\Facades\Auth;

class SimCardController extends Controller
{
    protected $cache;
    protected $parlacom;

    public function __construct(ParlacomService $parlacom, Cache $cache)
    {
        $this->cache    = $cache;
        $this->parlacom = $parlacom;
    }//end construct



    public function index(Request $request)
    {
        $operator = $this->parlacom->getOperator($request->carrier);
        // dd(['request'=>$request->carrier,'$operator'=>$operator]);
        $carrier  = $request->get('carrier');
        
        // // $request->login = 'ciagps';
        // dd($request->login);

        $total=0;
        $totalOnline=0;
        $percTotalOnline=0;
        $totalOffline=0;
        $percTotalOffline=0;
        $total24NoConnect=0;
        $percTotal24NoConnect=0;
        $total60NoConnect=0;
        $percTotal60NoConnect=0;

        $login = auth()->user()->login;
        
        if (!is_null($request->login)) {
            $login = $request->login;
        }

        // $countLogin = auth()->user()->login;
        // if (auth()->user()->hasRoles(['SuperAdmin', 'Admin'])) {
        //     $countLogin = 'admin';
        //     if (!is_null($request->login)) { 
        //         $countLogin = $request->login;
        //     }             
        // }

        $consumptions = [];
        $consumptions[] = null;
        $consumptions[] = -0.01;

        for ($i = 5; $i <= 100; $i++) {
            $consumptions[] = $i * (-1);
        }
        for ($i = 0; $i <= 100; $i++) {
            $consumptions[] = $i;
        }

        $paginate = 15;
        $page     = $request->get('page', 1);
        $offSet   = ($page * $paginate) - $paginate;

        $login = request()->get('login') ?? auth()->user()->login;
        $user = User::where('login', $login)->first();
        if (is_null($user)) {
            $user = User::where('login', auth()->user()->login)->first();
        }
        
        // $users = User::clients($user->id)->get();
        $users = Auth::user();

        $simcards = new LengthAwarePaginator([], 0, 35, 1);

        // if (auth()->user()->isAdmin()) {
        //     if (is_null($request->login)) {
        //         $login = null;
        //     }
        // }
        if (is_null($request->login)) {
            $login = null;
        }
        
        if (!is_null($login)) {
            
            $cache_index =  auth()->user()->login . "_". $login . "_" . $carrier . "_" . $request->get('page', 1);

            // $data = \Cache::remember($cache_index, 10, function() use($login, $carrier) {
                // return collect($this->parlacom->getAllLines($login, $carrier));
                // return collect($this->parlacom->findCallerId($login, null, $carrier));
            // });

            // if (auth()->user()->hasRoles(['SuperAdmin', 'Admin']) == false) {
            //     if ($request->login != auth()->user()->login) {
            //         $login = auth()->user()->login;
            //     }
            // }
                
            $totalBase = $this->parlacom->totalPins($login, $request->carrier);;
            if (!empty($totalBase)) {
                $totalBase = $totalBase[0]['total'];
            }

            $totalRow = $request->get('total', 35);
            $ini   = ($totalRow * $request->get('page', 1) - $totalRow);

            //workin with cache
            $hasSearch = false;

            if ($request->get('fcallerid')) {
                $hasSearch = true;
            } else if ($request->get('ficcid')) {
                $hasSearch = true;
            } else if ($request->get('fdesc')) {
                $hasSearch = true;
            } else if ($request->get('fdata')) {
                $hasSearch = true;
            } else if ($request->get('flastdate')) {
                $hasSearch = true;
            } else if ($request->get('fcons')) {
                $hasSearch = true;
            } else if ($request->get('fmrc')) {
                $hasSearch = true;
            } else if ($request->get('fmrc')) {
                $hasSearch = true;
            }

            $fimei     = null;
            $fcallerid = $request->get('fcallerid', null);
            $ficcid    = $request->get('ficcid', null);
            $fdata     = $request->get('fdata', null);
            $flastdate = $request->get('flastdate', null);
            $fcons     = $request->get('fcons', null);
            $fmrc      = $request->get('fmrc', null);
            $fcredit   = $request->get('fcredit', null);

            if (!is_null($request->get('fimei'))) {
                $fimei = $request->get('fimei');
                $hasSearch = true;
            } else if (!is_null($request->get('fdesc'))) {
                $fimei = $request->get('fdesc');
            }

            $orb  = 'asc';
            $orbf = 'simcard';

            if ($request->has('ordCallerId') && !empty($request->get('ordCallerId'))) {
                $sort = $request->get('ordCallerId', 'asc');
                if ($sort == 'desc') {
                    $orb  = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordDesc') && !empty($request->get('ordDesc'))) {
                $orbf= 'description';
                $sort = $request->get('ordDesc', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordIccid') && !empty($request->get('ordIccid'))) {
                $orbf= 'iccid';
                $sort = $request->get('ordIccid', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordImei') && !empty($request->get('ordImei'))) {
                $orbf= 'imei';
                $sort = $request->get('ordImei', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordLastCon') && !empty($request->get('ordLastCon'))) {
                $orbf= 'lastuse';
                $sort = $request->get('ordLastCon', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordCreated') && !empty($request->get('ordCreated'))) {
                $orbf= 'lastuse';
                $sort = $request->get('ordCreated', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordMrc') && !empty($request->get('ordMrc'))) {
                $orbf= 'plan';
                $sort = $request->get('ordMrc', 'asc');
                if ($sort == 'desc') {
                    $orb= 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordBalance') && !empty($request->get('ordBalance'))) {
                $orbf= 'credit';
                $sort = $request->get('ordBalance', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordCredit') && !empty($request->get('ordCredit'))) {
                $orbf= 'credit';
                $sort = $request->get('ordCredit', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordCons') && !empty($request->get('ordCons'))) {
                $orbf= 'consumption';
                $sort = $request->get('ordCons', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            $search          = null;
            $searchBy        = 'callerid';
            $searchcid       = $request->get('fcallerid', null);
            $searchiccid     = $request->get('ficcid', null);
            $searchdesc      = $request->get('fdesc', null);
            $searchmrc       = $request->get('fmrc', null);
            $searchcredit    = $request->get('fcredit', null);
            $searchlastdate  = $request->get('flastdate', null);
            $percent         = $request->get('fcons', null);
            $searchonline    = $request->get('fonline', null);
            
            if (!is_null($searchcid)) {
                $searchBy = 'searchcid';
                $search   = $searchcid;
            }
            
            if (!is_null($searchiccid)) {
                $searchBy = 'searchiccid';
                $search   = $searchiccid;
            }

            if (!is_null($searchdesc)) {
                $searchBy = 'searchdesc';
                $search   = $searchdesc;
            }
            
            if (!is_null($searchmrc)) {
                $searchBy = 'searchmrc';
                $search   = $searchmrc;
            }
            
            if (!is_null($searchcredit)) {
                $searchBy = 'searchcredit';
                $search   = $searchcredit;
            }
            
            if (!is_null($searchlastdate)) {
                $searchBy = 'searchlastdate';
                $search   = $searchlastdate;
            }
            
            if (!is_null($percent)) {
                $searchBy = 'percent';
                $search   = $percent;
            }
            
            if (!is_null($searchonline)) {
                $searchBy = 'online';
                $search   = $searchonline;
            }

           

            $data = [];

            $data = collect($this->parlacom->simcards($login, $carrier, $search, $searchBy, $ini, $totalRow, $orb, $orbf));
            
            $simcards = new LengthAwarePaginator($data, $totalBase, 35, $page);
            $simcards->setPath('simcards');
            $simcards->appends([
                'login' => $login,
                'carrier' => $request->carrier
            ]);   
        }
        
        $sessionid = $this->parlacom->getSessionID();

        debug(['simcards'=>$simcards,'operator'=>$operator,'users'=>$users,'consumptions'=>$consumptions,'sessionid'=>$sessionid ]);
        
        return view('home', compact('simcards', 'operator', 'users', 'consumptions', 'sessionid'));
    }//end methods


    






    // não esta sendo usada..... 
    public function simcardsAPi(Request $request)
    {
        $operator = $this->parlacom->getOperator($request->carrier);
        // dd(['request'=>$request->carrier,'$operator'=>$operator]);
        $carrier  = $request->get('carrier');
        
        // // $request->login = 'ciagps';
        // dd($request->login);

        $total=0;
        $totalOnline=0;
        $percTotalOnline=0;
        $totalOffline=0;
        $percTotalOffline=0;
        $total24NoConnect=0;
        $percTotal24NoConnect=0;
        $total60NoConnect=0;
        $percTotal60NoConnect=0;

        $login = auth()->user()->login;
        
        if (!is_null($request->login)) {
            $login = $request->login;
        }

        // $countLogin = auth()->user()->login;
        // if (auth()->user()->hasRoles(['SuperAdmin', 'Admin'])) {
        //     $countLogin = 'admin';
        //     if (!is_null($request->login)) { 
        //         $countLogin = $request->login;
        //     }             
        // }

        $consumptions = [];
        $consumptions[] = null;
        $consumptions[] = -0.01;

        for ($i = 5; $i <= 100; $i++) {
            $consumptions[] = $i * (-1);
        }
        for ($i = 0; $i <= 100; $i++) {
            $consumptions[] = $i;
        }

        $paginate = 15;
        $page     = $request->get('page', 1);
        $offSet   = ($page * $paginate) - $paginate;

        $login = request()->get('login') ?? auth()->user()->login;
        $user = User::where('login', $login)->first();
        if (is_null($user)) {
            $user = User::where('login', auth()->user()->login)->first();
        }
        
        // $users = User::clients($user->id)->get();
        $users = Auth::user();

        $simcards = new LengthAwarePaginator([], 0, 35, 1);

        // if (auth()->user()->isAdmin()) {
        //     if (is_null($request->login)) {
        //         $login = null;
        //     }
        // }
        if (is_null($request->login)) {
            $login = null;
        }
        
        if (!is_null($login)) {
            
            $cache_index =  auth()->user()->login . "_". $login . "_" . $carrier . "_" . $request->get('page', 1);

            // $data = \Cache::remember($cache_index, 10, function() use($login, $carrier) {
                // return collect($this->parlacom->getAllLines($login, $carrier));
                // return collect($this->parlacom->findCallerId($login, null, $carrier));
            // });

            // if (auth()->user()->hasRoles(['SuperAdmin', 'Admin']) == false) {
            //     if ($request->login != auth()->user()->login) {
            //         $login = auth()->user()->login;
            //     }
            // }
                
            $totalBase = $this->parlacom->totalPins($login, $request->carrier);;
            if (!empty($totalBase)) {
                $totalBase = $totalBase[0]['total'];
            }

            $totalRow = $request->get('total', 35);
            $ini   = ($totalRow * $request->get('page', 1) - $totalRow);

            //workin with cache
            $hasSearch = false;

            if ($request->get('fcallerid')) {
                $hasSearch = true;
            } else if ($request->get('ficcid')) {
                $hasSearch = true;
            } else if ($request->get('fdesc')) {
                $hasSearch = true;
            } else if ($request->get('fdata')) {
                $hasSearch = true;
            } else if ($request->get('flastdate')) {
                $hasSearch = true;
            } else if ($request->get('fcons')) {
                $hasSearch = true;
            } else if ($request->get('fmrc')) {
                $hasSearch = true;
            } else if ($request->get('fmrc')) {
                $hasSearch = true;
            }

            $fimei     = null;
            $fcallerid = $request->get('fcallerid', null);
            $ficcid    = $request->get('ficcid', null);
            $fdata     = $request->get('fdata', null);
            $flastdate = $request->get('flastdate', null);
            $fcons     = $request->get('fcons', null);
            $fmrc      = $request->get('fmrc', null);
            $fcredit   = $request->get('fcredit', null);

            if (!is_null($request->get('fimei'))) {
                $fimei = $request->get('fimei');
                $hasSearch = true;
            } else if (!is_null($request->get('fdesc'))) {
                $fimei = $request->get('fdesc');
            }

            $orb  = 'asc';
            $orbf = 'simcard';

            if ($request->has('ordCallerId') && !empty($request->get('ordCallerId'))) {
                $sort = $request->get('ordCallerId', 'asc');
                if ($sort == 'desc') {
                    $orb  = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordDesc') && !empty($request->get('ordDesc'))) {
                $orbf= 'description';
                $sort = $request->get('ordDesc', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordIccid') && !empty($request->get('ordIccid'))) {
                $orbf= 'iccid';
                $sort = $request->get('ordIccid', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordImei') && !empty($request->get('ordImei'))) {
                $orbf= 'imei';
                $sort = $request->get('ordImei', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordLastCon') && !empty($request->get('ordLastCon'))) {
                $orbf= 'lastuse';
                $sort = $request->get('ordLastCon', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordCreated') && !empty($request->get('ordCreated'))) {
                $orbf= 'lastuse';
                $sort = $request->get('ordCreated', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            if ($request->has('ordMrc') && !empty($request->get('ordMrc'))) {
                $orbf= 'plan';
                $sort = $request->get('ordMrc', 'asc');
                if ($sort == 'desc') {
                    $orb= 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordBalance') && !empty($request->get('ordBalance'))) {
                $orbf= 'credit';
                $sort = $request->get('ordBalance', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordCredit') && !empty($request->get('ordCredit'))) {
                $orbf= 'credit';
                $sort = $request->get('ordCredit', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }
            
            if ($request->has('ordCons') && !empty($request->get('ordCons'))) {
                $orbf= 'consumption';
                $sort = $request->get('ordCons', 'asc');
                if ($sort == 'desc') {
                    $orb = 'desc';
                }
                $hasSearch = true;
            }

            $search          = null;
            $searchBy        = 'callerid';
            $searchcid       = $request->get('fcallerid', null);
            $searchiccid     = $request->get('ficcid', null);
            $searchdesc      = $request->get('fdesc', null);
            $searchmrc       = $request->get('fmrc', null);
            $searchcredit    = $request->get('fcredit', null);
            $searchlastdate  = $request->get('flastdate', null);
            $percent         = $request->get('fcons', null);
            $searchonline    = $request->get('fonline', null);
            
            if (!is_null($searchcid)) {
                $searchBy = 'searchcid';
                $search   = $searchcid;
            }
            
            if (!is_null($searchiccid)) {
                $searchBy = 'searchiccid';
                $search   = $searchiccid;
            }

            if (!is_null($searchdesc)) {
                $searchBy = 'searchdesc';
                $search   = $searchdesc;
            }
            
            if (!is_null($searchmrc)) {
                $searchBy = 'searchmrc';
                $search   = $searchmrc;
            }
            
            if (!is_null($searchcredit)) {
                $searchBy = 'searchcredit';
                $search   = $searchcredit;
            }
            
            if (!is_null($searchlastdate)) {
                $searchBy = 'searchlastdate';
                $search   = $searchlastdate;
            }
            
            if (!is_null($percent)) {
                $searchBy = 'percent';
                $search   = $percent;
            }
            
            if (!is_null($searchonline)) {
                $searchBy = 'online';
                $search   = $searchonline;
            }

           

            $data = [];

            $data = collect($this->parlacom->simcards($login, $carrier, $search, $searchBy, $ini, $totalRow, $orb, $orbf));
            
            $simcards = new LengthAwarePaginator($data, $totalBase, 35, $page);
            $simcards->setPath('simcards');
            $simcards->appends([
                'login' => $login,
                'carrier' => $request->carrier
            ]);   
        }
        
        $sessionid = $this->parlacom->getSessionID();

        debug(['simcards'=>$simcards,'operator'=>$operator,'users'=>$users,'consumptions'=>$consumptions,'sessionid'=>$sessionid ]);

        return response()->json(['simcards' => $simcards, 'operator'=>$operator]);
        
        // return view('home', compact('simcards', 'operator', 'users', 'consumptions', 'sessionid'));

    }//end methods


}//end class
