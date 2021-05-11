<div style="grid-area: {{ $gridArea }};{{ $show ? '' : 'display:none' }}"
     class="card position-relative"
        {{ $refreshIntervalInSeconds ? "wire:poll.{$refreshIntervalInSeconds}s" : ''  }}>
    <div class="position-absolute overflow-hidden p-4"
         @if($fade) style="-webkit-mask-image: linear-gradient(black, black calc(100% - 1rem), transparent)" @endif>

        {{ $slot }}

    </div>
</div>
