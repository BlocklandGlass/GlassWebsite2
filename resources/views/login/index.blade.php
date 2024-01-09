@extends('templates.app')

@section('title', 'Login')

@section('description', 'Log in to Blockland Glass.')

@section('content')
    <h2>Login</h2>
    <p>To log in with Steam, please click the button below:</p>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="{{ route('login.auth', [], false) }}">
                <img src="{{ asset('img/sits_01.webp') }}" alt="A sign in through Steam button" />
            </a>
        </div>
    </div>
@endsection
