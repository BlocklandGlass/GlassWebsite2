@extends('templates.app')

@section('title', $user->name)

@section('description', $user->name)

@section('content')
    <h2>{{ $user->name }}</h2>
    <p>This is {{ $user->name }}.</p>
@endsection
