@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <register-component :provinces='{{ $provinces }}'></register-component>

            
        </div>
    </div>
@endsection
