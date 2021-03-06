<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use \Illuminate\Database\QueryException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function userProfile()
    {
        return view('users.profile');
    }//end methods


    public function adminListUsers(){

        Gate::authorize('acess-admin');

        $users = DB::table('users')
        ->select('first_name','login','email','cpf_cnpj')
        ->orderBy('first_name','asc')
        ->paginate(6);  
                
        //dd($users);

        return view('admin.users.list-users',['users'=>$users]);
                

    }//end methods


    public function adminFormCreateUser()
    {

        Gate::authorize('acess-admin');             
            
        return view('admin.users.create-user');

        
    }//end method


    public function adminCreateUser(Request $request)
    {    

        try{

            DB::table('users')->insert([
                'name'=>$request->name,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'login'=>$request->login,
                'email'=>$request->email,
                'cpf_cnpj'=>$request->cpf_cnpj,
                'company'=>$request->company,
                'company_fantasy'=>$request->company_fantasy,
                'state_registration'=>$request->state_registration,
                'municipal_registration'=>$request->municipal_registration,
                'address'=>$request->address,
                'password'=>$request->password,
                
            ]);

        }catch(QueryException $ex){

            // dd($ex->getMessage()); 

            return redirect()                   
            ->route('admin-user')
            ->withError('N??o foi possivel cadastrar, Usuario j?? existe na base de clientes');

        }

       

        return redirect()                   
                   ->route('admin-user')
                   ->withSuccess('Sucesso ao cadastrar');
                // ->withSuccess -> joga essa msg em uma sessao temporaria
                //assim consigo pegar na view
                //    ->with('success','Sucesso ao cadastrar') 
    


    }//end method


}//end class
