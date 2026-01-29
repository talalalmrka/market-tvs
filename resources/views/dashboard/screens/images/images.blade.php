<div class="grid grid-cols-5 gap-4">
    @foreach ($this->slides as $slide)
        <div class="col">
            <img src="{{ $slide }}" alt="">
        </div>
    @endforeach
</div>
