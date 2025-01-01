@section('subNav')
    <ul>
        <li><a href="{{ route('my-account.my-addons', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-package"></i>My Add-Ons</a></li><li><a href="{{ route('my-account.link', [], false) }}" class="navBtn"><i class="bx-fw bx bx-link"></i>Link</a></li>
    </ul>
@endsection
