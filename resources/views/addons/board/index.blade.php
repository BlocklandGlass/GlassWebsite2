@extends('templates.app')

@section('title', $addonBoard->name)

@section('description', 'Browse add-ons in the '.$addonBoard->name.' board.')

@section('subNav')
    <ul>
        <li><a href="{{ route('addons.boards', [], false) }}" class="navBtn">Boards</a></li><li><a href="{{ route('addons.rtb', [], false) }}" class="navBtn">RTB Archive</a></li>
    </ul>
@endsection

@section('content')
    <h2>{{ $addonBoard->name }}</h2>
    <div class="paginator" id="top">{{ $approvedAddons->onEachSide(1)->fragment('top')->links() }}</div>
    <table class="boardTable" cellspacing="0">
        <thead>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Uploader
                </th>
                <th>
                    Downloads
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($approvedAddons as $addon)
                <tr>
                    <td>
                        <strong><a href="{{ route('addons.addon', ['id' => $addon->id], false) }}">{{ $addon->name }}</a></strong>
                        <br />
                        <small>{{ $addon->summary }}</small>
                    </td>
                    <td>
                        <a href="{{ route('users.blid', ['id' => $addon->blid->id], false) }}">{{ $addon->blid->name }}</a>
                    </td>
                    <td>
                        {{ number_format($addon->total_downloads) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="paginator" id="bottom">{{ $approvedAddons->onEachSide(1)->fragment('bottom')->links() }}</div>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#">Back to the top</a>
        </div>
    </div>
@endsection
