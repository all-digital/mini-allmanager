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
         
            
          {{-- table --}}
        <div class="row d-flex justify-content-center">
          <div class="card col-sm-2 col-md-2">

            <div class="btn-group">
                <button type="button" class="btn btn-primary"> Operadoras </button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu render-operation">
                  {{-- <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Separated link</a> --}}
                  </div>
              </div>

            </div>
        </div>                        
          {{-- {{$simcards = true}} --}}
        @if (isset($simcards))
          <div class="row d-flex justify-content-center">
              @include('tables.table-main')            
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
              operation +=  `<a class="dropdown-item" href="/simcards/operator?carrier=${ope.carrier}&login=${login}">${ope.operadora}</a>`
            });
            console.log(operation)
            document.querySelector('.render-operation').innerHTML = operation
    }

</script>  
@endpush



 

  
