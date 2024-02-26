@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6 ">
                <div class="card justify-content-center p-5">


                    <div class="card-body "> <br>
                        <h5 class="text-center mb-5">
                            <img style="max-height:64px; max-width:300px" src="/images/DSWD-DVO-LOGO.png" contain
                                alt="DSWD" /> <br><br>

                          <b>  Assistance to Individuals in Crisis (AICS)</b>
                          <hr>
                        </h5>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                               
                                <div class="col-md-12">
                                    <input id="username" type="text" placeholder="Username"
                                        class="form-control  form-control-lg @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autocomplete="username" autofocus>
                                    <!-- <input id="username" type="text"  value="icts.fo11@dswd.gov.ph" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus> -->

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                 <div class="col-md-12">
                                    <input id="password" type="password" placeholder="Password"
                                        class="form-control  form-control-lg  @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-auto  ">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-auto  ">
                                    <div class="form-check">
                                        {!! NoCaptcha::display() !!}


                                        @if ($errors->has('g-recaptcha-response'))
                                            <strong
                                                style="color:#dc3545">{{ $errors->first('g-recaptcha-response') }}</strong>
                                        @endif

                                    </div>
                                </div>
                            </div>

                           
                            <div class="row px-5">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Login') }}
                                </button>
    
                             
                            </div>
                          


                            <div class="row justify-content-center" >
                                <div class="col-auto  ">
                                  
                                    <hr>
                                    @guest

                                        @if (Route::has('register'))
                                        Dont have an account? <a class="btn btn-link" href="{{ route('register') }}">{{ __('Register') }} here</a>
                                        @endif

                                    @endguest

                                    @if (Route::has('password.request'))
                                       |  <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif



                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
