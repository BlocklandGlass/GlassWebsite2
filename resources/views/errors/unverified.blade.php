@extends('templates.app')

@section('title', 'Account Not Verified')

@section('description', 'Only users with a verified account can access this.')

@section('content')
    <h2>Account Not Verified</h2>
    <p>Only users with a verified account can access this.</p>
    <p>Please finish <a href="{{ route('my-account.link', [], false) }}">linking your account</a> and then try again.</p>
@endsection
