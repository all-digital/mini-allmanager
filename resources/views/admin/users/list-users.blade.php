@extends('layouts.app')

@section('content') 

 
<div class="content">

    <div class="container-fluid">
        <div class="row d-flex justify-content-center">

            <a class="btn btn-primary btn-block col-2 mt-0" href="{{url('admin-user')}}">
                Criar Usuario
            </a>

          <div class="col-md-10">          
              <div class="card">
              <div class="card-header card-header-primary d-flex justify-content-center">
                <h4 class="card-title h3">Tabela de Usuarios</h4>
                {{-- <p class="card-category"> Here is a subtitle for this table</p> --}}
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                      <tr>                                          
                        <th> <strong>Nome</strong></th>                  
                        <th><strong>Login</strong></th>
                        <th><strong>Email</strong></th>
                        <th><strong>CPF CNPJ</strong></th>
                      </tr>
                    </thead>                     
                     
                    <tbody>                     

                        @foreach ($users as $item)

                          <tr>
                            <td>{{$item->first_name}}</td>
                            <td>{{$item->login}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->cpf_cnpj}}</td>                                                       
                          </tr>

                        @endforeach

                    </tbody>
                  </table>
                </div>
                {{$users->links()}}  
              </div>
            </div>
          </div>
          
                        
                      
                      
                    

          
   
</div>  

 {{-- loader and modal --}}
 <div class="modal-container-loading to-hide">
  <div class="c-loading"> </div>
</div>

@endSection
@push('inner-js')
<script>
   $(document).ready( function () {       
                
    });   

  var login = @json(auth()->user()->login)

 

  
</script>
@endpush  