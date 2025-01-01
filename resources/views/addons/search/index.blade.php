@extends('templates.app')

@section('title', 'Search')

@section('description', 'Search all add-on boards.')

@include('components.addons.subnav')

@section('breadcrumb')
    <div class="row">
        <div class="col-xs">
            <span><a href="{{ route('addons', [], false) }}" class="link">Add-Ons</a> <i class="bx-fw bx bxs-chevron-right"></i><a href="{{ route('addons.boards', [], false) }}" class="link">Boards</a> <i class="bx-fw bx bxs-chevron-right"></i>Search</span>
        </div>
    </div>
@endsection

@section('content')
    <div class="row center-xs">
        <div class="col-xs">
            <img src="{{ asset('img/logo.webp') }}" title="Blockland Glass" alt="The Blockland Glass logo in color" style="max-width: 100%; margin-bottom: 10px;" />
            <form method="get" action="{{ route('addons.search', ['#top'], false) }}">
                <input class="searchBar" type="text" name="query" placeholder="Search" value="{{ $query }}" onfocus="let focus_value = this.value; this.value = ''; this.value = focus_value;" autofocus>
            </form>
            <br />
        </div>
    </div>
    <div class="paginator" id="top">{{ $approvedAddons->onEachSide(1)->fragment('top')->withQueryString()->links() }}</div>
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
            @forelse ($approvedAddons as $addon)
                <tr>
                    <td>
                        <strong><a href="{{ route('addons.addon', ['id' => $addon->id], false) }}" class="link">{{ $addon->name }}</a></strong>
                        <br />
                        <small>{{ $addon->summary }}</small>
                    </td>
                    <td>
                        <a href="{{ route('users.blid', ['id' => $addon->blid->id], false) }}" class="link">{{ $addon->blid->name }}</a>
                    </td>
                    <td>
                        {{ number_format($addon->total_downloads) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">
                        No add-ons were found matching your search query.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="paginator" id="bottom">{{ $approvedAddons->onEachSide(1)->fragment('bottom')->withQueryString()->links() }}</div>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#" class="link">Back to the top</a>
        </div>
    </div>
@endsection
