@extends('templates.app')

@section('title', 'RTB Archive')

@section('description', 'Browse the RTB Archive.')

@section('subNav')
    @include('addons.subnav')
@endsection

@section('breadcrumb')
    <div class="row">
        <div class="col-xs">
            <span><a href="{{ route('addons', [], false) }}" class="link">Add-Ons</a> <i class="bx-fw bx bxs-chevron-right"></i>RTB Archive</span>
        </div>
    </div>
@endsection

@section('content')
    <div class="row center-xs">
        <div class="col-xs">
            <img src="{{ asset('img/rtb_logo.webp') }}" title="Return to Blockland (RTB)" alt="The Return to Blockland (RTB) logo" style="max-width: 100%; margin-bottom: 10px;" />
            <p>Please check back later.</p>
        </div>
    </div>
@endsection
