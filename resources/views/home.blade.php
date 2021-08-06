@extends('layouts.app')

@section('content') 

      <div class="content">
        <div class="container-fluid">      


          {{-- imagem carousel --}}
          <div class="row d-flex justify-content-center">
            <div class="card col-md-10">
              {{-- <div class="card-header card-header-primary d-flex justify-content-center">
                <h4 class="card-title ">Imagem da semana</h4>                    
              </div> --}}              
                @include('carousel.carousel')                           
            </div>
          </div>

          {{-- card  --}}
          
          @include('cards.card-simcards')

           
          {{-- card informativo --}}
         
            
          
        {{-- <div class="row d-flex justify-content-center">
          <div class="card col-sm-2 col-md-2">
            <div class="btn-group">
                <button type="button" class="btn btn-primary"> Operadoras </button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu render-operation">                  
                  </div>
              </div>
            </div>
        </div>   --}}

          {{-- table --}}
          {{-- {{$simcards = true}} --}}
        @if (isset($simcards))

        {{-- msg de que email smartsim cancelar ou renovar foi enviado com sucesso --}}
        @if (Session::has('success'))
          <div class="alert alert-info d-flex justify-content-center" role="alert">
            <strong><span class="text-white">{{ Session::get('success') }}</span></strong>
          </div>         
        @endif 
          {{-- loader --}}
          <div>
             <div class="c-loader to-hide"> </div>
          <div>

            {{-- table --}}
          <div class="row d-flex justify-content-center">
              @include('tables.table-main')            
          </div>
        @else        
            {{-- loader --}}
          <div>
              <div class="c-loader to-hide"> </div>
          <div>
            {{-- table empty --}} 
          <div class="row d-flex justify-content-center"> 
                @include('tables.table-main-empty') 
          </div>
        @endif
                                       
        



              
      </div>                       
    </div>
      
@endsection

@push('home-js')
<script>
   $(document).ready( function () {
    fetchApiOperation()                 
       
    });

    var login = @json(auth()->user()->login);
                console.log(login)

    function fetchApiOperation()
        {        
            // load total lines
                        
                fetch('/api/dashboard/total-lines?login='+login,{ 
                method:'GET',    
                headers:{"Content-type":"application/json"}
                })            
                .then(res=> res.json())
                .then(res => {
                console.log(res)
                renderOperator(res)
                sumLine(res);
            })
            .catch(error =>{ console.log('error api ', error)})

    }//end function


    function renderOperator(data){
            let operation =""

            data.data.forEach(ope => {
              operation +=  `<a class="dropdown-item" href="/simcards/operator?carrier=${ope.carrier}&login=${login}" @click="$store.alpine.loader()">${ope.operadora}</a>`
            });
            console.log(operation)
            document.querySelector('.render-operation').innerHTML = operation
    }

</script>  
@endpush



 

  
