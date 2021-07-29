@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"> <strong class="h3">{{ __('Login') }}</strong> </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/newLogin/valid') }}">
                        @csrf

                            @if($errors->all())
                            @foreach ($errors->all() as $error)
                            <div class="alert alert-danger d-flex justify-content-center" role="alert">
                                {{$error}}
                            </div>
                            @endforeach
                            @endif

                        <div class="form-group row">
                            <label for="login" class="col-md-4 col-form-label text-md-right">Login</label>

                            <div class="col-md-6">
                                <input id="login" type="text" class="form-control " name="login"   placeholder="login">                               
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password">                               
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>newLogin</title>
</head>
<body>
    <h1>newLogin</h1>


    <form action="{{url('/newLogin/valid')}}" method="POST">
        @csrf

        @if($errors->all())
           @foreach ($errors->all() as $error)
               <h1>{{$error}}</h1>
           @endforeach
        @endif
        <div>
            <label for="login">login</label>
            <input type="text" name="login" placeholder="login">
        </div>

        <div>
            <label for="password">password</label>
            <input type="text" name="password" placeholder="password">
        </div>
        <button>enviar</button>
    </form>
</body>
</html> --}}