@extends('templates.app')

@section('title', 'Edit: '.$addon->name)

@section('description', 'Edit an add-on.')

@section('head')
    <link rel="stylesheet" href="{{ asset('js/sceditor/themes/default.min.css') }}">
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
                @slot('selected', true)
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
            <form method="post">
                @csrf
                @if ($addon->is_draft)
                    <div class="formSection">
                        <div class="formHeader">Add-On Board</div>
                        <div class="formBody">
                            <label for="board">Select the board for the add-on:</label>
                            <select id="board" name="board" required>
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}" {{ $board->id == old('board', $addon->addon_board_id) ? 'selected' : '' }}>{{ $board->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="formSection">
                    <div class="formHeader">Add-On Details</div>
                    <div class="formBody">
                        <label for="name">Enter the name of the add-on:</label>
                        @if (! $completed['file'])
                            <input class="input" id="name" name="name" value="{{ old('name', $addon->name) }}" type="text" placeholder="Rocket Launcher" maxlength="50" required>
                        @else
                            <input class="input" id="name" name="name" value="{{ $addon->name }}" type="text" disabled>
                        @endif
                        <label for="summary">Enter a brief summary of the add-on:</label>
                        <input class="input" id="summary" name="summary" value="{{ old('summary', $addon->summary) }}" type="text" placeholder="A weapon that fires rockets." maxlength="80" required>
                        <label for="description">Enter a detailed description of the add-on:</label>
                        <textarea id="description" name="description" onkeyup="characterCount(this)" style="height: 400px;">{{ old('description', $addon->description) }}</textarea>
                        <small id="characterLimit">Character Limit (with BBCode): <span id="wordCount">0</span>/{{ $maxCharacterLength }}</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs center-xs">
                        <button type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/sceditor/sceditor.min.js') }}"></script>
    <script src="{{ asset('js/sceditor/icons/monocons.js') }}"></script>
    <script src="{{ asset('js/sceditor/formats/bbcode.js') }}"></script>

    <script>
        const textarea = document.getElementById('description');

        sceditor.create(textarea, {
            plugins: 'undo',
            format: 'bbcode',
            icons: 'monocons',
            style: '{{ asset('js/sceditor/themes/content/default.min.css') }}',
            width: '100%',
            resizeWidth: false,
            toolbar: 'bold,italic,underline|left,center,right|font,size,color,removeformat|cut,copy,paste|bulletlist,orderedlist|image,link,unlink|youtube|maximize,source',
            emoticonsRoot: '{{ url('js/sceditor') }}/',
            emoticons: {
                dropdown: {
                    ':cookie:': 'emoticons/cookie.gif',
                    ':cookieMonster:': 'emoticons/CookieMonster.gif',
                    ':panda:': 'emoticons/sadPanda.gif',
                    ':iceCream:': 'emoticons/iceCream.gif',
                    ':nes:': 'emoticons/nes.gif'
                }
            },
        });

        function characterCount() {
            let count = textarea._sceditor.val().length;

            $('#wordCount').text(count);

            if (count > {{ $maxCharacterLength }}) {
                $('#characterLimit').css('color', '#f00');
            } else {
                $('#characterLimit').css('color', '');
            }
        }

        $(document).ready(function () {
            characterCount();
        });

        sceditor.instance(textarea).selectionChanged(characterCount);
        sceditor.instance(textarea).keyUp(characterCount);
    </script>
@endsection
