<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>teste</title>
</head>
<body>

    {{-- {{dd($simcards)}} --}}
    
    <h1>Pagina teste</h1>

    <div class="col-md-8">
    <div class="card">
      <div class="card-header card-header-primary d-flex justify-content-center">
        <h4 class="card-title ">Linhas </h4>
        {{-- <p class="card-category"> Here is a subtitle for this table</p> --}}
      </div>
      <div class="card-body">        
        <div class="table-responsive">
          <table class="table" role="grid" id="tabela"> {{-- id="table_id"  --}}
            <thead class=" text-primary">
              <tr>
                <th>Ações</th>       
                <th>Callerid</th>                 
                <th>Iccid</th>
                <th>Ultima Conexão</th>
                <th>ON/OFF</th>
                <th>Data Ativação</th>
                <th>Data Vencimento</th>
                <th>Franquia e Consumo</th>                                                         
                              
              </tr>                         
            </thead>
            <tbody>
                @forelse ($simcards as $i => $line)
                    <tr>    
                            <td>button</td>
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
                            <td>vencimentos</td>   
                            <td>{{ $line['balance'] }} MB</td>                      
                            <td>{{$line['consumption']}}%</td> 
                    </tr> 
                @empty
                    vaziooooooooooooooooooooooooooo
                @endforelse           
                                           
                   
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
                  
                
                 
                                         
                  
                
                                                                                                                                                        

    <a href="{{url('/newLogin/logout')}}"> logout </a>

</body>
</html>