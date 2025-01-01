@extends('templates.app')

@section('title', $addonBoard->name)

@section('description', 'Browse add-ons in the '.$addonBoard->name.' board.')

@include('components.addons.subnav')

@section('breadcrumb')
    <div class="row">
        <div class="col-xs">
            <span><a href="{{ route('addons', [], false) }}" class="link">Add-Ons</a> <i class="bx-fw bx bxs-chevron-right"></i><a href="{{ route('addons.boards', [], false) }}" class="link">Boards</a> <i class="bx-fw bx bxs-chevron-right"></i>{{ $addonBoard->name }}</span>
        </div>
    </div>
@endsection

@section('content')
    <h2>{{ $addonBoard->name }}</h2>
    <br />
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
                        No add-ons have been uploaded to this board yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="paginator" id="bottom">{{ $approvedAddons->onEachSide(1)->fragment('bottom')->links() }}</div>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#" class="link">Back to the top</a>
        </div>
    </div>
@endsection
