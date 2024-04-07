@extends('templates.app')

@section('title', 'Link')

@section('description', 'Link your BLID(s) to your account.')

@section('subNav')
    @include('my-account.subnav')
@endsection

@section('breadcrumb')
    <div class="row">
        <div class="col-xs">
            <span><a href="{{ route('my-account', [], false) }}" class="link">My Account</a> <i class="bx-fw bx bxs-chevron-right"></i>Link</span>
        </div>
    </div>
@endsection

@section('content')
    <h2>Link</h2>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @else
        @if (session()->has('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
    @endif
    <p>To link a BLID from your Steam account, please submit it below:</p>
    <form method="post">
        @csrf
        <input type="text" name="blid" placeholder="BLID" style="padding: 10px; width: 100%; box-sizing: border-box;" autocomplete="off" autofocus>
    </form>
    <p>Your Glass account status is currently: <strong style="color: {{ $color }};">{{ $status }}</strong></p>
    @if (!auth()->user()->primary_blid)
        <p><strong>To finish verifying your account, you must select a primary BLID. This can only be done once:</strong></p>
        <form method="post">
            @csrf
            <select name="primary" required>
                <option value=""></option>
                @foreach (auth()->user()->blids as $blid)
                    <option value="{{ $blid->id }}">{{ $blid->id }}</option>
                @endforeach
            </select>
            <button type="submit">Select</button>
        </form>
        <p>The primary BLID you select will become your username on the Glass website. Both your primary and alternative BLID(s) will be used for verifying ownership of past uploaded add-ons and in-game authentication.</p>
    @endif
    <p>Your linked BLIDs:</p>
    @if (auth()->user()->blids->isNotEmpty())
        <ul>
            @foreach (auth()->user()->blids as $blid)
                @if ($blid->id === auth()->user()->primary_blid)
                    <li><strong>{{ $blid->id }} ({{ $blid->name }})</strong></li>
                @else
                    <li>{{ $blid->id }} ({{ $blid->name }})</li>
                @endif
            @endforeach
        </ul>
    @else
        <p>Your Glass account is not currently linked to any BLIDs.</p>
    @endif
@endsection
