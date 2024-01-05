@extends('templates.app')

@section('title', 'My Account')

@section('description', 'Your account page.')

@section('subNav')
    <ul>
        <li><a href="{{ route('my-account.link', [], false) }}" class="navBtn">Link</a></li>
    </ul>
@endsection

@section('content')
    <h2>My Account</h2>
    <p>Hello <strong>{{ auth()->user()->name }}</strong>!</p>
@endsection
