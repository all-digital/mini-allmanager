<!--
=========================================================
Material Dashboard - v2.1.2
=========================================================

Product Page: https://www.creative-tim.com/product/material-dashboard
Copyright 2020 Creative Tim (https://www.creative-tim.com)
Coded by Creative Tim

=========================================================
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    
    <!-- CSRF Token -->      
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Allcom') }}</title>

    <!-- Scripts  and Styles-->

    {{-- As dependencias do template esta dando conflito com app.js --}}
    {{-- <script src="{{ asset('js/app.js') }}" defer></script>     --}}

    {{-- loader animante --}}
    <link href="{{ asset('/css/animante.css') }}" rel="stylesheet" type="text/css" />

    {{-- css  template   --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <link href="../assets/css/material-dashboard.css" rel="stylesheet" />
        
      
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    {{-- <link rel="icon" type="image/png" href="../assets/img/favicon.png"> --}}
      
      <!--     Fonts and icons     -->
      <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />

      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

      {{-- assistive-touch  bot??o flutuante para auxiliar na navega????o pela aplica????o--}}

      {{-- datatables --}}
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
        

      <style>
          #assistive-touch{
              position: fixed;
              /* background-color:#8e24aa; */              
              top: 155px;
              right:70px;
              width: 200px;
              /* opacity:0.8; */
              cursor:pointer;
              font-weight: 900;
          }

          #assistive-touch .list-group-item:hover{

            background-color:#ab47bc;           
        }

      </style>
     

</head>


<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
               
                @auth
                    <a href="{{'home'}}"><img width="200px" style="cursor: pointer;" src="{{asset('new-logo.png')}}"></a> 
                @endauth
                @guest
                    <img width="200px" src="{{asset('new-logo.png')}}">
                @endguest
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('newLogin') }}">{{ __('Login') }}</a>
                            </li>
                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                               
                                {{-- <div class="btn-group">
                                    <button type="button" class="btn btn-primary"> {{ Auth::user()->name }}</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                      <a class="dropdown-item" href="#">Action</a>
                                      <a class="dropdown-item" href="#">Another action</a>
                                      <a class="dropdown-item" href="#">Something else here</a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="#">Separated link</a>
                                    </div>
                                  </div> --}}

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->company_fantasy }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('/newLogin/logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{url('/newLogin/logout') }}" method="GET" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    {{-- assistive touch --}}
    @auth
        @include('layouts.assistive-touch')    
    @endauth

  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
   
  <!--  Plugin for Sweet Alert -->
  <script src="../assets/js/plugins/sweetalert2.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc --> 
  <script src="../assets/js/material-dashboard.min.js" type="text/javascript"></script>
  {{-- datatables --}}
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
  {{-- alpinejs  --}}
  
  <script src="{{ asset('assets/js/alpinejs@323.min.js') }}" defer></script>    

  {{-- alpinejs os metodos nesse script s??o compartilhados globalmente na app --}}
<script>   

    document.addEventListener('alpine:init', () => {
        Alpine.store('alpine', {
            open:false,            
            name:'',

            toggle(){
                this.open = this.open ? false : true                
            },
            chanceName(value){
                this.name = value
            },
            loading(){               
                var loading = document.querySelector(".modal-container-loading")
                loading.classList.toggle('to-hide')
            }
        })
    })
   
</script>
@stack('home-js')
@stack('inner-js')
 
</body>
</html>
       
        
            

   

                       
    


    


    
