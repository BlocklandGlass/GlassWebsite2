@if ($paginator->hasPages())
    @foreach ($elements as $element)
        @if (is_string($element))
            <strong>...</strong>
        @elseif (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    [<strong>{{ $page }}</strong>]
                @else
                    <a href="{{ $url }}" class="link">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach
@else
    [<strong>1</strong>]
@endif
