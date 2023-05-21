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
            @foreach ($addonBoard->approved_addons->sortByDesc('created_at') as $addon)
                <tr>
                    <td>
                        <strong><a href="{{ route('addons.addon', ['id' => $addon->id], false) }}">{{ $addon->name }}</a></strong>
                        <br />
                        <small>{{ $addon->summary }}</small>
                    </td>
                    <td>
                        <strong>{{ $addon->blid->name }}</strong>
                    </td>
                    <td>
                        {{ number_format($addon->total_downloads) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#">Back to the top</a>
        </div>
    </div>
@endsection
