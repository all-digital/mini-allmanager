@extends('layouts.app')

@section('content') 

<style>
#form-create-user input {
    color:blueviolet;
}
</style>

 
<div class="content">
    <div class="container-fluid">

      <div class="row d-flex justify-content-center">
        <div class="col-md-6 alert alert-warning row to-hide msgErrorApi" role="alert">    
          Ocorreu alguma problema, tente novamente mais tarde!
        </div>
      </div> 
      
      <div class="row d-flex justify-content-center">
        <div class="col-md-6 alert alert-danger row to-hide msgErrorLogin" role="alert">    
          Login deve ser preenchido!
        </div>
      </div> 

      <div class="row">
        <div class="col-md-10">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">Cadastro de novos Usuarios</h4>
              <p class="card-category"></p>
            </div>
            <div class="card-body">

            @if($errors->any())
              <div class="alert alert-danger mt-2">
                  @foreach ($errors->all() as $item)
                      <p>{{$item}}</p>
                  @endforeach
              </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success d-flex justify-content-center">
                    {{session('success')}}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger d-flex justify-content-center">
                    {{session('error')}}
                </div>
            @endif


              <form id="form-create-user" action="{{url('admin-user-create')}}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Nome</label> --}}
                      <input type="text" class="form-control" name="name" id="name" Value="Nome">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">First Name</label> --}}
                      <input type="text" class="form-control" name="first_name" id="first_name" value="Primeiro Nome">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Last Name</label> --}}
                      <input type="text" class="form-control" name="last_name" id="last_name" value="Ultimo Nome">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Email</label> --}}
                      <input type="text" class="form-control" name="email" id="email" value="Email">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">login</label> --}}
                      <input type="text" class="form-control" name="login" id="login" value="Login">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">CPF CNPJ</label> --}}
                      <input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj" value="Cpf Cnpj">
                    </div>
                  </div>
                </div>               
                
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Company</label> --}}
                      <input type="text" class="form-control" name="company" id="company" value="Compania">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Company Fantasy</label> --}}
                      <input type="text" class="form-control" name="company_fantasy" id="company_fantasy" value="Nome Fantasia">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">State Registration</label> --}}
                      <input type="text" class="form-control" name="state_registration" id="state_registration" value="Registro Estadual">
                    </div>
                  </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group bmd-form-group">
                          {{-- <label class="bmd-label-floating">Municipal Registration</label> --}}
                          <input type="text" class="form-control" name="municipal_registration" id="municipal_registration" value="Registro Municipal">
                        </div>
                    </div>

                    <div class="col-md-3">
                       <div class="form-group bmd-form-group">
                         {{-- <label class="bmd-label-floating">Address</label> --}}
                         <input type="text" class="form-control" name="address" id="address" value="Endereço">
                       </div>
                    </div>

                    <div class="col-md-4">
                       <div class="form-group bmd-form-group">
                         {{-- <label class="bmd-label-floating">Password</label> --}}
                         <input type="text" class="form-control" name="password" id="password" value="Senha">
                       </div>
                    </div>              
                </div>
                
                <button type="submit" class="btn btn-primary btn-round pull-right">Enviar</button>
                <div class="clearfix"></div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card card-profile">            
            <div class="card-body">              
              <h4 class="card-title">Importa cliente do Allmanager</h4>
                <form class="form-group" id="form-TochargeUser">
                    <label class="bmd-label-floating">Informe o login do cliente</label>
                    <input type="text" class="form-control" name="loginImport" id="loginImport">
                    <button class="btn btn-primary btn-round">Enviar</button>
                </form>              
            </div>
          </div>
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

  var formTochargeUser = document.getElementById('form-TochargeUser')

  formTochargeUser.addEventListener('submit', function(e){
      e.preventDefault()

      let login = formTochargeUser.loginImport.value

      ToChargeUser(login) 

  })
   

function ToChargeUser(login)
    {     

          if(!!login.trim()){            
            var msgErrorLogin = document.querySelector('.msgErrorLogin')
            msgErrorLogin.classList.add('to-hide')
          }else{
            var msgErrorLogin = document.querySelector('.msgErrorLogin')
            msgErrorLogin.classList.remove('to-hide')
          }

          var loading = document.querySelector(".modal-container-loading")
          loading.classList.toggle('to-hide')

          fetch('http://localhost:8082/api/mini-allmanager/infoUser?login='+login,{ 
          method:'POST',    
          headers:{"Content-type":"application/json"}
          })            
          .then(res=> res.json())
          .then(res => {
             console.log("ToChargeUser allmanager => ",res)

             mountFild(res[0])

            loading = document.querySelector(".modal-container-loading")
            loading.classList.toggle('to-hide')

            let msgErrorApi = document.querySelector('.msgErrorApi')
            msgErrorApi.classList.add('to-hide')

          })
          .catch(error =>{

            loading = document.querySelector(".modal-container-loading")
            loading.classList.toggle('to-hide')

            let msgErrorApi = document.querySelector('.msgErrorApi')
            msgErrorApi.classList.remove('to-hide')
             
                console.log('ToChargeUser api error => ', error)           
          })




          function mountFild(data)
          {

            
            let formUser = document.getElementById('form-create-user')

            formUser.name.value =                    data.name ? data.name : 'Nome'
            formUser.first_name.value =              data.first_name ? data.first_name : 'Primeiro Nome'
            formUser.last_name.value =               data.last_name ? data.last_name : 'Ultimo Nome'
            formUser.email.value =                   data.email ? data.email : 'Email'
            formUser.login.value =                   data.login ? data.login : 'Login'
            formUser.cpf_cnpj.value =                data.cpf_cnpj ? data.cpf_cnpj : 'CpF Cnpj'
            formUser.company.value =                 data.company ? data.company : 'Compania'
            formUser.company_fantasy.value =         data.company_fantasy ? data.company_fantasy : 'Nome Fantasia'
            formUser.state_registration.value =      data.state_registration ? data.state_registration :'Registro Estadual'
            formUser.municipal_registration.value =  data.municipal_registration ? data.municipal_registration :'Registro Municipal'
            formUser.address.value =                 data.address ? data.address : 'Endereço'
            formUser.password.value =                data.password ? data.password : 'Password'
            
          }
             
         
    }//end function

  
</script>
@endpush  