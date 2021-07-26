@extends('layouts.app')

@section('content') 

{{-- @include('layouts.navbar') --}}

 
    {{-- <div class=""> --}}

      <!-- Navbar -->
      <!-- menu responsivo -->     
      {{-- @include('layouts.menu-resposive-toggler') --}}
      <!-- End Navbar -->      
      <div class="content">
        <div class="container-fluid">      

          {{-- card  --}}
          <div class="row d-flex justify-content-center">



            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">sim_card</i>
                  </div>
                  <p class="card-category"><strong> Total de linhas </strong></p>
                  <h3 class="card-title">$5553422,245</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Last 24 Hours
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
                  <h3 class="card-title">$34,245</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Last 24 Hours
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
                  <h3 class="card-title">75</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i>Last 24 Hours
                  </div>
                </div>
              </div>
            </div>
           
          </div>


          {{-- chartjs --}}
          {{-- <div class="row d-flex justify-content-around">
              <div class="col-lg-4 col-md-6 col-sm-6 ">
                <canvas id="myChart" width="200" height="200"></canvas>
              </div>  
              
              
              <div class="col-lg-4 col-md-6 col-sm-6">
                <canvas id="myChart2" width="200" height="200"></canvas>
              </div> 
           </div> --}}

          
          
          {{-- teste --}}

         

          <div class="card mt-6">

            {{-- <div class="card-header card-header-primary  d-flex justify-content-center">
              <div>
                <h3 class="card-title">Graficos</h3>
               
              </div>
            </div> --}}


            {{-- <div class="card-body">
              <div class="row">
                

                <div class="col-md-6 d-flex justify-content-center">
                  <h4 class="card-title">Grafico de polarArea simcards</h4>
                  
               
                   
                  
                </div>
                
                
                <div class="col-md-6 d-flex justify-content-center">
                  <h4 class="card-title">Grafico de pie simcards </h4>
                  
                  
                                     

                </div>
                
              </div>
            </div>




            </div> --}}
          </div>
          {{-- teste --}}


            <div class="row d-flex justify-content-center ">
              <div class="col-md-8">

                <div class="card card-nav-tabs">
                  <h4 class="card-header card-header-info">Featured</h4>
                  <div class="card-body">
                    <h4 class="card-title">Special title treatment</h4>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#0" class="btn btn-primary">Go somewhere</a>
                  </div>
                </div>

              </div>
            </div>






          {{-- table --}}

          <div class="row d-flex justify-content-center ">

            <div class="col-md-10">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Simple Table</h4>
                  <p class="card-category"> Here is a subtitle for this table</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <tr><th>
                          ID
                        </th>
                        <th>
                          Name
                        </th>
                        <th>
                          Country
                        </th>
                        <th>
                          City
                        </th>
                        <th>
                          Salary
                        </th>
                      </tr></thead>
                      <tbody>
                        <tr>
                          <td>
                            1
                          </td>
                          <td>
                            Dakota Rice
                          </td>
                          <td>
                            Niger
                          </td>
                          <td>
                            Oud-Turnhout
                          </td>
                          <td class="text-primary">
                            $36,738
                          </td>
                        </tr>
                        <tr>
                          <td>
                            2
                          </td>
                          <td>
                            Minerva Hooper
                          </td>
                          <td>
                            Curaçao
                          </td>
                          <td>
                            Sinaai-Waas
                          </td>
                          <td class="text-primary">
                            $23,789
                          </td>
                        </tr>
                        <tr>
                          <td>
                            3
                          </td>
                          <td>
                            Sage Rodriguez
                          </td>
                          <td>
                            Netherlands
                          </td>
                          <td>
                            Baileux
                          </td>
                          <td class="text-primary">
                            $56,142
                          </td>
                        </tr>
                        <tr>
                          <td>
                            4
                          </td>
                          <td>
                            Philip Chaney
                          </td>
                          <td>
                            Korea, South
                          </td>
                          <td>
                            Overland Park
                          </td>
                          <td class="text-primary">
                            $38,735
                          </td>
                        </tr>
                        <tr>
                          <td>
                            5
                          </td>
                          <td>
                            Doris Greene
                          </td>
                          <td>
                            Malawi
                          </td>
                          <td>
                            Feldkirchen in Kärnten
                          </td>
                          <td class="text-primary">
                            $63,542
                          </td>
                        </tr>
                        <tr>
                          <td>
                            6
                          </td>
                          <td>
                            Mason Porter
                          </td>
                          <td>
                            Chile
                          </td>
                          <td>
                            Gloucester
                          </td>
                          <td class="text-primary">
                            $78,615
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>


          </div>
          
          




        </div>




      </div>
      {{-- footer --}}
      {{-- @include('layouts.footer') --}}

      @include('layouts.assistive-touch')
      
    </div>
    
  {{-- </div> --}}
 
  


@endsection





 

  
