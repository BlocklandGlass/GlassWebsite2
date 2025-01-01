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
                @slot('selected', true)
                @slot('completed', $completed['file'])
                @slot('optional', false)
            @endcomponent
        </div>
        @if ($addon->is_draft)
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
            <form method="post" enctype="multipart/form-data">
                @csrf
                <div class="formSection">
                    <div class="formHeader">Add-On File</div>
                    <div class="formBody">
                        <input accept=".zip" type="file" name="file" required>
                        <br />
                        <small>The file name must follow the add-on naming convention in Blockland e.g. <strong>Weapon_Rocket_Launcher.zip</strong></small>
                        <br />
                        <small>There is a file size limit of <strong>{{ $maxFileSize }} MB</strong>.</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs center-xs">
                        <button type="submit">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
