@extends('templates.app')

@section('title', 'Account Link')

@section('description', 'Link your BLID(s) to your Steam account.')

@section('subNav')
    <ul>
        <li><a href="{{ route('account.link', [], false) }}" class="navBtn">Link</a></li>
    </ul>
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
