@extends('templates.app')

@section('title', 'My Add-Ons')

@section('description', 'View your uploaded add-ons.')

@include('components.my-account.subnav')

@section('breadcrumb')
    <div class="row">
        <div class="col-xs">
            <span><a href="{{ route('my-account', [], false) }}" class="link">My Account</a> <i class="bx-fw bx bxs-chevron-right"></i>My Add-Ons</span>
        </div>
    </div>
@endsection

@section('content')
    <h2>My Add-Ons</h2>
    @foreach (auth()->user()->blids as $blid)
        @foreach ($blid->addons as $addon)
            <p><a href="{{ route('addons.addon', ['id' => $addon->id], false) }}" class="link">{{ $blid->id }} - {{ $addon->name }}</a></p>
        @endforeach
    @endforeach
@endsection
