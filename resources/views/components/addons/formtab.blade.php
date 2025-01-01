<a href="{{ $route }}" style="color: inherit; text-decoration: none;">
    <div style="background-color: @if ($selected) #ddd @else #eee @endif; padding: 10px;">
        @if (! $optional)
            @if (! $completed)
                <i class="bx bx-x" style="font-size: 1.5rem; color: #b31515;"></i>
            @else
                <i class="bx bx-check" style="font-size: 1.5rem; color: #15b358;"></i>
            @endif
        @else
            @if (! $completed)
                <i class="bx bx-question-mark" style="font-size: 1.5rem; color: #b3a615;"></i>
            @else
                <i class="bx bx-check" style="font-size: 1.5rem; color: #15b358;"></i>
            @endif
        @endif
        <br />
        {{ $name }}
    </div>
</a>