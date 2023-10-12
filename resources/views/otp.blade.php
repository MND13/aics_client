
@extends('layouts.app')

@section('content')

<otp-gen-component :user="{{Auth::user()}}"></otp-gen-component>


@endsection

