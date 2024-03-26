@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <register-component :provinces='{{ $provinces }}'></register-component>

            <div class="m-5 text-center">
               Already have an account? Please
                <a href="{{ route('login') }}">Login</a>

            </div>
        </div>
    </div>
@endsection
