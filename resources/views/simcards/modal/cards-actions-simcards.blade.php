<div id="smartsim-renovar{{$callerid}}" class="modal fade" role="dialog" aria-labelledby="smartsim" aria-hidden="true" x-data>
    <div class="modal-dialog" role="document">
    <div class="modal-content card">
        <div class="modal-header card-header card-header-success d-flex justify-content-center">
          
        <p class="h4">Renovação de linha {{$callerid}}</p>
        
        </div>
        <div class="modal-body card-body d-flex justify-content-center">
            
            <form class="d-none" action="{{url('/mail-simcards')}}" method="POST">
                @csrf
                <input type="hidden" name="callerid" value="{{$callerid}}" class="d-none">
                <input type="hidden" name="acao" value="renovar">

                <button type="submit" class="btn btn-success" @click="$store.alpine.loader()">Renovar</button>
            </form>
            
        </div>
    </div>
    </div>
</div>


<div id="smartsim-cancelar{{$callerid}}" class="modal fade" role="dialog" aria-labelledby="smartsim" aria-hidden="true" x-data>
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
                <input type="hidden" name="acao" value="cancelar">

                <button type="submit" class="btn btn-danger" @click="$store.alpine.loader()">Cancelar</button>
            </form>
            
            
        </div>
    </div>
    </div>
</div>