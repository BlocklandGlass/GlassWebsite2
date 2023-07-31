@extends('templates.app')

@section('title', $addon->name)

@section('description', $addon->summary) {{-- FIXME: Not all add-ons will have a summary filled. --}}

@if ($addon->no_index)
    @section('head')
        <meta name="robots" content="noindex">
    @endsection
@endif

@section('subNav')
    <ul>
        <li><a href="{{ route('addons.boards', [], false) }}" class="navBtn">Boards</a></li><li><a href="{{ route('addons.rtb', [], false) }}" class="navBtn">RTB Archive</a></li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs">
            <h2>{{ $addon->name }}</h2>
            <p>Uploaded by <strong>{{ $addon->blid->name }}</strong></p>
            <p>
                <img class="addonIcon" src="{{ asset('img/icons32/category.webp') }}" title="Board" alt="The add-on board icon." /> <a href="{{ route('addons.board', ['id' => $addon->addon_board_id], false) }}">{{ $addon->addon_board->name }}</a>
                <br />
                <img class="addonIcon" src="{{ asset('img/icons32/folder_vertical_zipper.webp') }}" title="File Name" alt="The add-on file name icon." /> {{ $addon->addon_uploads->last()->file_name }}
                <br />
                <img class="addonIcon" src="{{ asset('img/icons32/date.webp') }}" title="Uploaded" alt="The add-on upload date icon." /> {{ $addon->human_readable_created_at }}
                <br />
                <img class="addonIcon" src="{{ asset('img/icons32/inbox_download.webp') }}" title="Downloads" alt="The add-on download count icon." /> {{ number_format($addon->total_downloads) }}
            </p>
        </div>
        @if ($addon->addon_screenshots->isNotEmpty())
            <div class="col-xs">
                @if ($addon->addon_screenshots->count() < 3)
                    <div class="row end-xs">
                        @foreach ($addon->addon_screenshots->sortBy('display_order') as $addonScreenshot)
                            @if ($loop->index % 3 == 0)
                                </div><div class="row end-xs">
                            @endif
                            <div class="col-xs">
                                <a href="{{ asset('storage/'.$addonScreenshot->file_path) }}" target="_blank"> {{-- TODO: Re-add JavaScript picture viewer. --}}
                                    <img src="{{ asset('storage/'.$addonScreenshot->file_path) }}" alt="An add-on screenshot." style="max-width: 100%; max-height: 512px;" /> {{-- TODO: Move to stylesheet. --}}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row end-xs">
                        <div class="col-xs">
                            <a href="{{ asset('storage/'.$addon->addon_screenshots->sortBy('display_order')->first()->file_path) }}" target="_blank"> {{-- TODO: Re-add JavaScript picture viewer. --}}
                                <img src="{{ asset('storage/'.$addon->addon_screenshots->sortBy('display_order')->first()->file_path) }}" alt="An add-on screenshot." style="max-width: 100%; max-height: 512px;" /> {{-- TODO: Move to stylesheet. --}}
                            </a>
                        </div>
                    </div>
                    <div class="row end-xs">
                        @foreach ($addon->addon_screenshots->sortBy('display_order')->skip(1) as $addonScreenshot)
                            @if ($loop->index % 3 == 0)
                                </div><div class="row end-xs">
                            @endif
                            <div class="col-xs">
                                <a href="{{ asset('storage/'.$addonScreenshot->file_path) }}" target="_blank"> {{-- TODO: Re-add JavaScript picture viewer. --}}
                                    <img src="{{ asset('storage/'.$addonScreenshot->file_path) }}" alt="An add-on screenshot." style="max-width: 100%; max-height: 512px;" /> {{-- TODO: Move to stylesheet. --}}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-xs">
            <h3>Description</h3>
            {!! $addon->html_description !!}
        </div>
    </div>
    <div class="row center-xs">
        <div class="col-xs">
            @if ($available)
                <form method="post" action="{{ route('addons.download', ['id' => $addon->id], false) }}">
                    @csrf
                    <button class="btn green" type="submit">Download v{{ $addon->addon_uploads->last()->version }}</button>
                </form>
            @else
                <button class="btn" type="submit" disabled>Missing: Download v{{ $addon->addon_uploads->last()->version }}</button>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs">
            <h3>Comments ({{ number_format($addon->addon_comments->count()) }})</h3>
        </div>
    </div>
    <div class="addonComments">
        @foreach ($addon->addon_comments as $addonComment)
            <div class="row">
                <div class="col-xs-3 col-sm-2">
                    <strong>{{ $addonComment->blid->name }}</strong>
                    <br />
                    <small>{{ $addonComment->created_at }}</small>
                </div>
                <div class="col-xs">
                    {{ $addonComment->body }}
                </div>
            </div>
        @endforeach
    </div>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#">Back to the top</a>
        </div>
    </div>
@endsection
