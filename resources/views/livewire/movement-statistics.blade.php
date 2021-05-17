<div class="d-flex flex-wrap w-100" style="column-gap: 2rem; row-gap: 1rem;">
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
        <div class="{{ $loading ? '' : 'visually-hidden ' }}position-absolute h-100 w-100" style="background-color: rgba(241, 245, 249, 0.5)">
            <div class="w-100 d-flex flex-fill flex-column h-100 justify-content-center align-items-center position-relative text-center">
                <div class="spinner-border"></div>
            </div>
        </div>

        <div class="p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                    <div class="text-secondary tracking-wider text-uppercase fw-bold" style="font-size: var(--midas-font-size-xs) !important;">Alerted Persons</div>
                    <div style="color: #97A6BA; font-size: .75rem; font-weight: 500">Total number of alerted person</div>
                </div>
            </div>

            <div class="flex align-items-center mt-auto" style="min-height: 2.25rem !important;">
                <div class="d-flex flex-column">
                    <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ $alertedPersons }}</div>
                </div>
            </div>
        </div>
    </x-dashboard-tile>
</div>

<script defer>
    document.addEventListener('livewire:load', function () {
      // Send ajax request. Retrieve movement statistics and update component
      Livewire.on('dateRangeUpdated', (dateRange) => {
        @this.loading = true;

        let url = "{{ route('movement.summary') }}?start_date=" + dateRange.startDate + "&end_date=" + dateRange.endDate;

        sendRequest(url, 'get')
          .then((response) => {
            const data = response.data;

          @this.movementSummary = updateMovementStatistics(data.Inbound, data.Outbound);

          @this.alertedPersons = Intl.NumberFormat('GB').format(data.Alerts);

          @this.loading = false;

          }, (error) => {
            console.log(error);
          });
      });

        @if($refreshIntervalInSeconds > 0)
            setInterval(function () {
              let query = window.location.href.split('?').slice(1);
              let url = "{{ route('movement.summary') }}?" + query;

              sendRequest(url, 'get')
                .then((response) => {
                  const data = response.data;

                  @this.movementSummary = updateMovementStatistics(data.Inbound, data.Outbound);

                  @this.alertedPersons = Intl.NumberFormat('GB').format(data.Alerts);

                }, (error) => {
                  console.log(error);
                });
            }, {{$refreshIntervalInSeconds * 1000}})
        @endif
    });

    /**
     * @return {Object}
     */
    function updateMovementStatistics(Inbound, Outbound) {
      return {
        Inbound: Intl.NumberFormat('GB').format(Inbound),
        Outbound: Intl.NumberFormat('GB').format(Outbound)
      }
    }

    /**
     * @param url
     * @param method
     * @returns {AxiosPromise | *}
     */
    function sendRequest(url, method) {
      return axios(url, {
        method
      });
    }
</script>

