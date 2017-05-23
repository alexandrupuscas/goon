@extends('master')
@section('title', 'Home')
@section('content')
    <div class="container">
        <div class="content">
            @if(Auth::check())
                <div class="quote">You are now logged in!</div>
            @else
                <div class="quote">Our Home page!</div>
            @endif
        </div>
    </div>
@endsection