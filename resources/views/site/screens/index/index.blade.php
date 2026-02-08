<div>
    @if ($this->screens->isNotEmpty())
        <ul class="list-group">
            @foreach ($this->screens as $screen)
                <li class="list-group-item">
                    <a title="{{ $screen->name }}" target="_blank" href="{{ $screen->permalink }}">{{ $screen->name }}</a>
                </li>
            @endforeach
        </ul>
        {{ $this->screens->links() }}
    @endif

</div>
