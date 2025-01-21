@extends('templates.app')

@section('title', 'Edit: '.$addon->name)

@section('description', 'Edit an add-on.')

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
                @slot('selected', false)
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
                    @slot('selected', true)
                    @slot('completed', $completed['file'])
                    @slot('optional', false)
                @endcomponent
            </div>
        @endif
    </div>
    <br />
    <div class="row">
        <div class="col-xs">
            <form method="post">
                @csrf
                <div class="formSection">
                    <div class="formHeader">Add-On Publish</div>
                    <div class="formBody">
                        @if ($addon->is_draft)
                            @if ($completed['file'])
                                Publishing the add-on will take it out of the draft state, making it semi-public and submitting it for inspection.
                                <br />
                                <br />
                                During the inspection stage, the add-on will not be displayed publicly, and you will be unable to make any further file changes unless you <em>Unpublish</em> the add-on.
                                <br />
                                <br />
                                If the add-on is approved, it will be advertised on the website and the in-game Mod Manager, and you will be able to make file changes again (without the draft state requirement).
                                <br />
                                <br />
                                Click <strong>Publish</strong> when ready.
                            @else
                                The add-on is not yet ready for publishing, please complete all previous sections marked with an <i class="bx bx-x" style="color: #b31515;"></i>
                            @endif
                        @else
                            The add-on is published and is awaiting inspection.
                            <br />
                            <br />
                            Unpublishing the add-on will put it back in the draft state.
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs center-xs">
                        @if ($addon->is_draft)
                            @if ($completed['file'])
                                <button type="submit">Publish</button>
                            @else
                                <button type="submit" disabled>Publish</button>
                            @endif
                        @else
                            <button type="submit">Unpublish</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
