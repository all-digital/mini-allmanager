{{-- assistive touch button --}}
<div class="fixed-plugin" x-data>
    <div class="dropdown show-dropdown">
        <a href="#" @click.prevent="$store.alpine.toggle()">
            <span> <i class="fa fa-cog fa-2x"> </i> </span>
        </a>          
    </div>
</div>
       
     
     
{{-- panel float of assistive touch --}}
<div x-data x-show="$store.alpine.open" id="assistive-touch" style="z-index:10;"></div>    


<div class="card" style="width: 12rem; z-index:10;"  x-data x-show="$store.alpine.open" id="assistive-touch">
          <div class="card-header card-header-primary text-center" @click.prevent="$store.alpine.toggle()">
            <strong> Menu </strong> 
          </div>
        <ul class="list-group list-group-flush">

            <li class="list-group-item d-flex justify-content-between"
              onclick="event.preventDefault();
                document.getElementById('user-profile').submit();"> 

              <form id="user-profile" action="{{ url('userProfile') }}" method="GET" class="d-none">                         
              </form>  
              Perfil         
              <i class="fa fa-user-o fa-2x"></i>
            </li>

            @can('acess-admin')

                <li class="list-group-item d-flex justify-content-between"
                  onclick="event.preventDefault();
                    document.getElementById('user-create-admin').submit();"> 

                  <form id="user-create-admin" action="{{ url('admin-list-users') }}" method="GET" class="d-none">                         
                  </form>  
                  Usuarios         
                  <i class="fa fa-cog fa-2x"></i>
                </li>

            @endcan

          <li class="list-group-item d-flex justify-content-between">  
            <a href = "mailto:support@allcomtelecom.com?subject=Support allcom&body=Support" style="color:black;">Suporte </a> 
            <i class="fa fa-headphones fa-2x"></i>
          </li>

              <li class="list-group-item d-flex justify-content-between"
                onclick="event.preventDefault();
                document.getElementById('form-logout').submit();">
                        
                <form id="form-logout" action="{{url('/newLogin/logout') }}" method="GET" class="d-none">
                  @csrf         
                </form>
                Logout
                <i class="fa fa-power-off fa-2x"></i>       
              </li>
        </ul>
  </div>




