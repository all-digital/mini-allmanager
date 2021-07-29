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
              <div class="card-body">
                @include('carousel.carousel')
              </div>              
            </div>
          </div>

          {{-- card  --}}
          <div class="row d-flex justify-content-center">

              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                  <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons">sim_card</i>
                    </div>
                    <p class="card-category"><strong> Total de linhas </strong></p>
                    <h3 class="card-title" id="total-lines"></h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      <i class="material-icons">date_range</i> <span class="date-Current1"></span>
                    </div>
                  </div>
                </div>
              </div>
         
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                  <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons">date_range</i>
                    </div>
                    <p class="card-category"><strong>Linhas a vencer</strong></p>
                    <h3 class="card-title">0</h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      <i class="material-icons">date_range</i> <span class="date-Current2"></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                  <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons">signal_cellular_no_sim</i>
                    </div>
                    <p class="card-category"><strong>Linhas vencidas</strong></p>
                    <h3 class="card-title">0</h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      <i class="material-icons">date_range</i> <span class="date-Current3"></span>
                    </div>
                  </div>
                </div>
              </div>
           
          </div>
       
              {{-- card informativo --}}
         
              {{-- <div class="row d-flex justify-content-center">
                <div class="col-md-8">
                  @include('cards.card-information')
                </div>
              </div>  --}}

              


          {{-- table --}}

          <div class="row d-flex justify-content-center">

            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-primary d-flex justify-content-center">
                  <h4 class="card-title ">Linhas </h4>
                  {{-- <p class="card-category"> Here is a subtitle for this table</p> --}}
                </div>
                <div class="card-body">
                  <div >
                    <table class="table" id="table_id" role="grid">
                      <thead class=" text-primary">
                        <tr>
                          <th>Ações</th>                         
                          <th>Operadora</th>
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
                      <tr>
                        <td><button class="btn btn-sm btn-primary"><span class="material-icons">
                          mode_edit_outline
                          </span></button></td>
                        <td>Porto</td>
                        <td>002288483180</td>
                        <td>895510801570084</td>
                        <td>29/07/2021</td>
                        <td>Ativo</td>
                        <td>30/05/2021</td>
                        <td>30/05/2022</td>
                        <td>60%</td>                        
                      </tr> 
                      <tr>
                        <td><button class="btn btn-sm btn-primary"><span class="material-icons">
                          mode_edit_outline
                          </span></button></td>
                        <td>Porto</td>
                        <td>002288483180</td>
                        <td>895510801570084</td>
                        <td>28/07/2021</td>
                        <td>Ativo</td>
                        <td>30/03/2021</td>
                        <td>30/03/2022</td>
                        <td>85%</td>                                     
                           
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>


          </div>
      {{-- end table --}}

        {{-- </div> --}}
      </div>
      {{-- footer --}}
      {{-- @include('layouts.footer') --}}

      
      
    </div>
      
@endsection





 

  
