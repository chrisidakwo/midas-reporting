<div style="{{ $gridArea ? "grid-area: $gridArea" : '' }};{{ $show ? '' : 'display:none' }}"
     class="card {{ $gridArea ? '' : 'd-flex flex-column flex-fill' }}"
        {{ $refreshIntervalInSeconds ? "wire:poll.{$refreshIntervalInSeconds}s" : ''  }}>
    <div class="card-body p-0">
        <div class="overflow-hidden">
            {{ $slot }}
        </div>
    </div>
</div>

