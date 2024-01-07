@extends('templates.app')

@section('title', 'My Account')

@section('description', 'Your account page.')

@section('subNav')
    @include('my-account.subnav')
@endsection

@section('content')
    <h2>My Account</h2>
    <p>Hello <strong>{{ auth()->user()->name }}</strong>!</p>
@endsection
