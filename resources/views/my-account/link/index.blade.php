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
    <p>To link a BLID to your account, please submit it below:</p>
    <form method="post">
        @csrf
        <input type="text" name="blid" placeholder="BLID" style="padding: 10px; width: 100%; box-sizing: border-box;" autocomplete="off" autofocus>
    </form>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @else
        @if (isset($success))
            <div class="success">{{ $success }}</div>
        @endif
    @endif
    @if (auth()->user()->blids->isNotEmpty())
        <p>Your account is currently linked to:</p>
        <ul>
            @foreach (auth()->user()->blids as $blid)
                <li>{{ $blid->id }} ({{ $blid->name }})</li>
            @endforeach
        </ul>
    @else
        <p>Your account is not currently linked to any BLIDs.</p>
    @endif
@endsection
