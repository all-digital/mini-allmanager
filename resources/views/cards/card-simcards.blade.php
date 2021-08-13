<div class="row d-flex justify-content-center">

    <div class="col-lg-3 col-md-6 col-sm-6"  data-toggle="modal" data-target="#smartsim-total">
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

    <div class="col-lg-3 col-md-6 col-sm-6" data-toggle="modal" data-target="#smartsim-vencer">
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

    <div class="col-lg-3 col-md-6 col-sm-6" data-toggle="modal" data-target="#smartsim-vencidos">
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

    {{-- modal --}}

    @include('modal.cards-simcards')
      
  {{-- modal --}}
</div>
@push('inner-js')
<script>
   $(document).ready( function () {
        fetchApi()
        updateDate() 
        simcardsAPi()           
       
    });
       
    // fetch api total
    function fetchApi()
    {                    
          var login = @json(auth()->user()->login)
                                  
          fetch('/api/dashboard/total-lines?login='+login,{ 
          method:'GET',    
          headers:{"Content-type":"application/json"}
          })            
          .then(res=> res.json())
          .then(res => {
          // console.log(res)
          renderOperationTotal(res);
          sumLine(res);
          })
          .catch(error =>{ console.log('error api total-lines ', error)})

    }//end function


    function simcardsAPi()
    {          
          fetch('http://localhost:8082/api/mini-allmanager/simcards/operator?login='+login,{ 
          method:'GET',    
          headers:{"Content-type":"application/json"}
          })            
          .then(res=> res.json())
          .then(res => {
             console.log("simcards allmanager => ",res)        
          })
          .catch(error =>{ console.log('allmanager api error => ', error)})
         
    }//end function




    //total for cards of total
    function sumLine(data)
    {
          const resultado = data.data.map(i => i.total).reduce(function(acum, atual){
             return acum + atual
          })
          document.getElementById('total-lines').innerHTML = resultado    

    }//end function 




    //date for cards
    function updateDate()
    {
        date = new Date()

        dateCurrent = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`

        document.querySelector('.date-Current1').innerHTML = dateCurrent
        document.querySelector('.date-Current2').innerHTML = dateCurrent
        document.querySelector('.date-Current3').innerHTML = dateCurrent
    }//end function


    //created tag li with operation
    function renderOperationTotal(data){
        let info =""

        data.data.forEach(ope => {

        info +=  `<li> <strong>${ope.operadora} </strong>  - <span>${ope.total}</span> </li>`

        });
            console.log(info)
            document.querySelector('.info-simcards').innerHTML = info
    }//end function



</script>  
@endpush
    