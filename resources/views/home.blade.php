@extends('layouts.app')

@section('content')

<div id="app">
    <app :user="{{ $user }}"><app/>
</div>

@endsection
