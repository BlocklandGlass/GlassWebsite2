@extends('templates.app')

@section('title', 'Edit: '.$addon->name)

@section('description', 'Edit an add-on.')

@section('head')
    <link rel="stylesheet" href="{{ asset('js/lightbox2/lightbox.min.css') }}">
@endsection

@include('components.addons.subnav')

@section('breadcrumb')
    <div class="row">
        <div class="col-xs">
            <span><a href="{{ route('addons', [], false) }}" class="link">Add-Ons</a> <i class="bx-fw bx bxs-chevron-right"></i><a href="{{ route('addons.boards', [], false) }}" class="link">Boards</a> <i class="bx-fw bx bxs-chevron-right"></i><a href="{{ route('addons.board', ['id' => $addon->addon_board_id], false) }}" class="link">{{ $addon->addon_board->name }}</a> <i class="bx-fw bx bxs-chevron-right"></i>Edit: {{ $addon->name }}</span>
        </div>
    </div>
@endsection

@section('content')
    <h2>Edit: {{ $addon->name }}</h2>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @else
        @if (session()->has('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
    @endif
    <br />
    <div class="row">
        <div class="col-xs center-xs">
            @component('components.addons.formtab')
                @slot('route', route('addons.edit.details', ['id' => $addon->id], false))
                @slot('name', 'Details')
                @slot('selected', false)
                @slot('completed', true)
                @slot('optional', false)
            @endcomponent
        </div>
        <div class="col-xs center-xs">
            @component('components.addons.formtab')
                @slot('route', route('addons.edit.screenshots', ['id' => $addon->id], false))
                @slot('name', 'Screenshots')
                @slot('selected', true)
                @slot('completed', $completed['screenshots'])
                @slot('optional', true)
            @endcomponent
        </div>
        <div class="col-xs center-xs">
            @component('components.addons.formtab')
                @slot('route', route('addons.edit.file', ['id' => $addon->id], false))
                @slot('name', 'File')
                @slot('selected', false)
                @slot('completed', $completed['file'])
                @slot('optional', false)
            @endcomponent
        </div>
        @if ($addon->latest_approved_addon_upload === null)
            <div class="col-xs center-xs">
                @component('components.addons.formtab')
                    @slot('route', route('addons.edit.publish', ['id' => $addon->id], false))
                    @slot('name', 'Publish')
                    @slot('selected', false)
                    @slot('completed', $completed['file'])
                    @slot('optional', false)
                @endcomponent
            </div>
        @endif
    </div>
    <br />
    <div class="row">
        <div class="col-xs">
            <div class="formSection">
                <div class="formHeader">Add-On Screenshots</div>
                <div class="formBody">
                    Screenshots are optional but strongly advised.
                    <br />
                    <br />
                    <fieldset>
                        @if ($addon->addon_screenshots?->count() > 0)
                            <div class="row">
                                @foreach ($addon->addon_screenshots->sortBy('display_order') as $screenshot)
                                    <div class="col-xs">
                                        <a data-lightbox="screenshot" href="{{ asset('storage/'.$screenshot->file_path) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$screenshot->file_path) }}" alt="An add-on screenshot." style="max-width: 100%; max-height: 512px; background-color: #fff;" /> {{-- TODO: Move to stylesheet. --}}
                                        </a>
                                        <br />
                                        <form id="remove" action="{{ route('addons.edit.screenshots', $addon->id, false) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $screenshot->id }}">
                                            <button class="button" type="submit">
                                                <i class="bx bxs-trash"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('addons.edit.screenshots', $addon->id, false) }}" method="post">
                                            @method('patch')
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $screenshot->id }}">
                                            @if (! $loop->first)
                                                <button class="button" type="submit" name="action" value="<">
                                                    <i class="bx bxs-left-arrow"></i>
                                                </button>
                                            @endif
                                            @if (! $loop->last)
                                                <button class="button" type="submit" name="action" value=">">
                                                    <i class="bx bxs-right-arrow"></i>
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            No screenshots added.
                        @endif
                    </fieldset>
                    <br />
                    <form id="upload" action="{{ route('addons.edit.screenshots', $addon->id, false) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <fieldset>
                            <label for="screenshot">Upload one screenshot at a time:</label>
                            <br />
                            <br />
                            <input class="screenshot" accept=".png,.jpg,.jpeg" type="file" id="screenshot" name="screenshot" required>
                            <br />
                            <br />
                            <small>
                                You can upload a maximum of <strong>{{ $maxScreenshots }}</strong> screenshots.
                                <br />
                                There is a file size limit of <strong>{{ $maxScreenshotSize }} MB</strong> per screenshot.
                            </small>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-xs center-xs">
                    <button type="submit" form="upload">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/lightbox2/lightbox.min.js') }}"></script>

    <script>
        lightbox.option({
            'albumLabel': 'Screenshot %1 of %2',
            'fadeDuration': 0,
            'imageFadeDuration': 0,
            'resizeDuration': 0,
            'wrapAround': true,
            'disableScrolling': true
        });
    </script>
@endsection
