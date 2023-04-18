@extends('layouts.app')

@section('content')

<div id="app">
    ADMIN
    <app :user="{{ $user }}"><app/>
</div>

@endsection
