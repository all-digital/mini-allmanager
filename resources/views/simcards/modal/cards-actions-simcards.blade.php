<div id="smartsim-renovar{{$callerid}}" class="modal fade" role="dialog" aria-labelledby="smartsim" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content card">
        <div class="modal-header card-header card-header-success d-flex justify-content-center">
          
        <p class="h4">Renovação de linha {{$callerid}}</p>
        
        </div>
        <div class="modal-body card-body d-flex justify-content-center">

            {{-- <button type="button" class="btn btn-success" data-dismiss="modal"> Renovar </button> --}}
            {{-- <div class="btn btn-success" id="button-renovar">
            </div> --}}
            {{-- <div class="btn btn-success" id="button-renew" >
                <a class="text-white"  data-dismiss="modal" href="{{url('/mail')}}"
                   onclick="event.preventDefault();
                   document.getElementById('button-renew').submit();"> Renovar 
                </a>                
                <form id="button-renew" action="{{url('/mail')}}" method="GET" class="d-none">
                    @csrf
                    <input type="text" name="callerid" value="{{$callerid}}" class="d-none">
                    <input type="hidden" name="name" value="darcio">
                </form>
            </div> --}}

            <form class="d-none" action="{{url('/mail-simcards')}}" method="POST">
                @csrf
                <input type="hidden" name="callerid" value="{{$callerid}}" class="d-none">
                <input type="hidden" name="name" value="darcio">

                <button type="submit" class="btn btn-success">Renovar</button>
            </form>
            
        </div>
    </div>
    </div>
</div>


<div id="smartsim-cancelar{{$callerid}}" class="modal fade" role="dialog" aria-labelledby="smartsim" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content card">
        <div class="modal-header card-header card-header-danger d-flex justify-content-center">
          
        <p class="h4">Certeza de que deseja cancelar a linha {{$callerid}}</p>
        
        </div>
        <div class="modal-body card-body d-flex justify-content-center">

            {{-- <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancelar  </button> --}}
            <form class="d-none" action="{{url('/mail-simcards')}}" method="POST">
                @csrf
                <input type="hidden" name="callerid" value="{{$callerid}}" class="d-none">
                <input type="hidden" name="name" value="darcioCancelar">

                <button type="submit" class="btn btn-danger">Cancelar</button>
            </form>
            
            
        </div>
    </div>
    </div>
</div>