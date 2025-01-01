@section('subNav')
    @if (! auth()->user())
        <ul>
            <li><a href="{{ route('addons.boards', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-dashboard"></i>Boards</a></li><li><a href="{{ route('addons.rtb', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-archive"></i>RTB Archive</a></li><li>
        </ul>
    @else
        <ul>
            <li><a href="{{ route('addons.boards', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-dashboard"></i>Boards</a></li><li><a href="{{ route('addons.rtb', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-archive"></i>RTB Archive</a></li><li><a href="{{ route('addons.upload', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-cloud-upload"></i>Upload</a></li>
        </ul>
    @endif
@endsection
