@extends('templates.app')

@section('title', 'Add-Ons')

@section('description', 'Today\'s overview of trending and recently released add-ons.')

@include('components.addons.subnav')

@section('content')
    <h2>Add-Ons</h2>
    <p>Please go directly to the <a href="{{ route('addons.boards', [], false) }}" class="link">Boards</a> page for now.</p>
    <p>This overview page is still being rewritten, but you can still find Glass add-ons from above.</p>
@endsection
