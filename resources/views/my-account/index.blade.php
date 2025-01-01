@extends('templates.app')

@section('title', 'My Account')

@section('description', 'Your account page.')

@include('components.my-account.subnav')

@section('content')
    <h2>My Account</h2>
    <p>Hello <strong>{{ auth()->user()->name }}</strong>!</p>
@endsection
