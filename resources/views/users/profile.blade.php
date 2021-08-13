@extends('layouts.app')

@section('content') 
<div class="content">
    <div class="container-fluid">
      <div class="row d-flex justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">Perfil</h4>
              {{-- <p class="card-category">Complete your profile</p> --}}
            </div>
            <div class="card-body">
              <form>
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Company</label> --}}
                      <input type="text" class="form-control" disabled="" value="{{auth()->user()->company_fantasy}}">
                    </div>
                  </div>
                  {{-- <div class="col-md-3">
                    <div class="form-group bmd-form-group">
                      <label class="bmd-label-floating">Username</label>
                      <input type="text" class="form-control">
                    </div>
                  </div> --}}
                  <div class="col-md-4">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Company</label> --}}
                      <input type="text" class="form-control" disabled="" value="{{auth()->user()->login}}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group bmd-form-group">
                      {{-- <label class="bmd-label-floating">Company</label> --}}
                      <input type="text" class="form-control" disabled="" value="{{auth()->user()->email}}">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group bmd-form-group">
                     {{-- <label class="bmd-label-floating">Company</label> --}}
                     <input type="text" class="form-control" disabled="" value="{{auth()->user()->cpf_cnpj}}">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group bmd-form-group">
                     {{-- <label class="bmd-label-floating">Company</label> --}}
                     <input type="text" class="form-control" disabled="" value="{{auth()->user()->phone}}">
                    </div>
                  </div>
                </div>

                
                {{-- <button type="submit" class="btn btn-primary pull-right">Update Profile</button> --}}
                {{-- <div class="clearfix"></div> --}}
              </form>
            </div>
          </div>
        </div>
        {{-- <div class="col-md-4">
          <div class="card card-profile">
            <div class="card-avatar">
              <a href="javascript:;">
                <img class="img" src="../assets/img/faces/marc.jpg">
              </a>
            </div>
            <div class="card-body">
              <h6 class="card-category text-gray">CEO / Co-Founder</h6>
              <h4 class="card-title">Alec Thompson</h4>
              <p class="card-description">
                Don't be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...
              </p>
              <a href="javascript:;" class="btn btn-primary btn-round">Follow</a>
            </div>
          </div>
        </div> --}}

      </div>
    </div>
  </div>

@endSection
@push('inner-js')
<script>
  
</script>
@endpush  