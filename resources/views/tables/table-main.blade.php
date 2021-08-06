<div class="col-md-10">
    <div class="card">
      <div class="card-header card-header-primary d-flex justify-content-center">
        <h4 class="card-title ">Linhas - {{$operator ?? "" }} </h4>
        {{-- <p class="card-category"> Here is a subtitle for this table</p> --}}
      </div>
      <div class="card-body">  

        {{-- dropdown select operator --}}
        <div class="row d-flex justify-content-start" x-data >
          <div class="col-1 col-md-3 col-sm-2">  
            <div class="btn-group">
                <button type="button" class="btn btn-primary"> Operadoras </button>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu render-operation">                  
                  </div>
              </div>
            </div>
        </div>  


        <div class="table-responsive">
          <table class="table" role="grid" id="tabela">
            <thead class=" text-primary">
              <tr>
                <th><strong>Ações</strong></th>       
                <th><strong>Callerid</strong></th>                 
                <th><strong>Iccid</strong></th>
                <th><strong>Ultima Conexão</strong></th>
                <th><strong>ON/OFF</strong></th>
                <th><strong>Data Ativação</strong></th>
                <th><strong>Data Vencimento</strong></th>
                <th><strong>Franquia</strong></th> 
                <th><strong>Consumo</strong></th>  
              </tr>                 
            </thead>
                                                                                                                                                        
          <tbody>
            <tbody>
              @inject('helpers', 'App\Http\Controllers\Helpers\HelpersControllers')
              @forelse ($simcards as $i => $line)
                  <tr>    
                         <td>
                          <div class="btn-group">                        
                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" x-data >                                   
                                    @include('simcards.modal.index', ['callerid' => $line['callerid'],'name'=>'renovar'])
                                    @include('simcards.modal.index', ['callerid' => $line['callerid'],'name'=>'cancelar'])
                                </div>
                          </div>           
                         </td>
                            <td>{{$line['callerid']}}</td>
                         <td>{{ $line['iccid'] }}</td>
                         <td>{{ $line['lastConn'] }}</td>
                         <td>
                             @if ($line['status'] == 'Ativo')
                             Ativa
                             @else
                             Inativa
                             @endif
                         </td>
                         <td>{{$line['createdAt']}}</td>
                         <td>{{ $helpers->dataExpiration($line['createdAt']) }}</td>   
                         <td>{{ $line['balance'] }} MB</td>                      
                         <td>{{$line['consumption']}}%</td> 
                 </tr> 
                 @include('simcards.modal.cards-actions-simcards',['callerid' => $line['callerid']]) 
             @empty
                  
             @endforelse                                           
                 
            </tbody>  
          </table>
        </div>
      </div>
    </div>
  </div>

    
@push('inner-js')    
 <script>
    // $(document).ready( function () {
    //     $('#table_id').DataTable({
    //         "scrollX": true,                                
    //         responsive: true,                        
    //     });
    // });
       
</script>   
@endpush