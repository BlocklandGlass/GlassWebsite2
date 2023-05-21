@extends('templates.app')

@section('title', 'RTB Archive')

@section('description', 'Browse the RTB Archive.')

@section('subNav')
    <ul>
        <li><a href="{{ route('addons.boards', [], false) }}" class="navBtn">Boards</a></li><li><a href="{{ route('addons.rtb', [], false) }}" class="navBtn">RTB Archive</a></li>
    </ul>
@endsection

@section('content')
    <div class="row center-xs">
        <div class="col-xs">
            <img src="{{ asset('img/rtb_logo.webp') }}" title="Return to Blockland (RTB)" alt="The Return to Blockland (RTB) logo" style="max-width: 100%; margin-bottom: 10px;" />
            <p>Please check back later.</p>
        </div>
    </div>
@endsection
