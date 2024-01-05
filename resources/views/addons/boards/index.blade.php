@extends('templates.app')

@section('title', 'Boards')

@section('description', 'Browse the main index of all add-on boards.')

@section('subNav')
    <ul>
        <li><a href="{{ route('addons.boards', [], false) }}" class="navBtn">Boards</a></li><li><a href="{{ route('addons.rtb', [], false) }}" class="navBtn">RTB Archive</a></li>
    </ul>
@endsection

@section('content')
    <div class="row center-xs">
        <div class="col-xs">
            <img src="{{ asset('img/logo.webp') }}" title="Blockland Glass" alt="The Blockland Glass logo in color" style="max-width: 100%; margin-bottom: 10px;" />
            <form method="get" action="{{ route('addons.search', ['#top'], false) }}">
                <input class="searchBar" type="text" name="query" placeholder="Search" autofocus>
            </form>
            <br />
        </div>
    </div>
    @foreach ($addonBoardGroups as $addonBoardGroup)
        <table class="boardGroupTable" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="3">
                        {{ $addonBoardGroup->name }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($addonBoardGroup->boards as $addonBoard)
                    <tr>
                        <td>
                            <img src="{{ asset('img/icons32/' . $addonBoard->icon . '.webp') }}" alt="The {{ $addonBoard->name }} board icon." />
                        </td>
                        <td>
                            <a href="{{ route('addons.board', ['id' => $addonBoard->id], false) }}" class="link">{{ $addonBoard->name }}</a>
                        </td>
                        <td>
                            {{ number_format($addonBoard->approved_addons_count) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#" class="link">Back to the top</a>
        </div>
    </div>
@endsection
