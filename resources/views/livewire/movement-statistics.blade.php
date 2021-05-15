<div class="d-flex flex-wrap w-100" style="column-gap: 2rem; row-gap: 0.25rem;">
    <x-dashboard-tile refreshIntervalInSeconds="10">
        <div class="{{ $loading ? '' : 'visually-hidden ' }}position-absolute h-100 w-100" style="background-color: rgba(241, 245, 249, 0.5)">
            <div class="w-100 d-flex flex-fill flex-column h-100 justify-content-center align-items-center position-relative text-center">
                <div class="spinner-border"></div>
            </div>
        </div>

        <div class="p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                    <div class="text-secondary tracking-wider text-uppercase fw-bold" style="font-size: var(--midas-font-size-xs) !important;">Inbound Movements</div>
                    <div style="color: #97A6BA; font-size: .75rem; font-weight: 500">Total number of inbound movements</div>
                </div>
            </div>

            <div class="flex align-items-center mt-auto" style="min-height: 2.25rem !important;">
                <div class="d-flex flex-column">
                    <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ $movementSummary['Inbound'] }}</div>
                </div>
            </div>
        </div>
    </x-dashboard-tile>

    <x-dashboard-tile refreshIntervalInSeconds="10">
        <div class="{{ $loading ? '' : 'visually-hidden ' }}position-absolute h-100 w-100" style="background-color: rgba(241, 245, 249, 0.5)">
            <div class="w-100 d-flex flex-fill flex-column h-100 justify-content-center align-items-center position-relative text-center">
                <div class="spinner-border"></div>
            </div>
        </div>

        <div class="p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                    <div class="text-secondary tracking-wider text-uppercase fw-bold" style="font-size: var(--midas-font-size-xs) !important;">Outbound Movements</div>
                    <div style="color: #97A6BA; font-size: .75rem; font-weight: 500">Total number of outbound movements</div>
                </div>
            </div>

            <div class="flex align-items-center mt-auto" style="min-height: 2.25rem !important;">
                <div class="d-flex flex-column">
                    <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ $movementSummary['Outbound'] }}</div>
                </div>
            </div>
        </div>
    </x-dashboard-tile>

    <x-dashboard-tile :loading="false">
        <div class="p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                    <div class="text-secondary tracking-wider text-uppercase fw-bold" style="font-size: var(--midas-font-size-xs) !important;">Alerted Persons</div>
                    <div style="color: #97A6BA; font-size: .75rem; font-weight: 500">Total number of alerted person</div>
                </div>
            </div>

            <div class="flex align-items-center mt-auto" style="min-height: 2.25rem !important;">
                <div class="d-flex flex-column">
                    <div class="fw-semibold text-5xl tracking-tighter lh-sm" id="alerted">23,963</div>
                </div>
            </div>
        </div>
    </x-dashboard-tile>
</div>

<script defer>
    document.addEventListener('livewire:load', function () {
      // Send ajax request. Retrieve movement statistics and update component
      let query = window.location.href.split('?').slice(1);

      $.ajax({
        url: "{{ route('movement.summary') }}" + '?' + query ,
        method: 'get',
        data: { },
        success: function (res) {
          @this.movementSummary = {
            Inbound: Intl.NumberFormat('GB').format(res.Inbound),
            Outbound: Intl.NumberFormat('GB').format(res.Outbound)
          };

          @this.loading = false;
        }
      });
    });
</script>

