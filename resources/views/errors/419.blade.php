@extends('layouts.app')

@section('title', 'Session Expired')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4">Session Expired</h1>
    <p class="lead">For your security, your session has expired. Please log in again.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
</div>
@endsection