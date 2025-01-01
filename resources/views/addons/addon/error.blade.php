@extends('templates.app')

@section('title', $title)

@section('description', $message)

@section('head')
    <meta name="robots" content="noindex">
@endsection

@section('content')
    <h2>{{ $title }}</h2>
    <p>{{ $message }}</p>
@endsection
