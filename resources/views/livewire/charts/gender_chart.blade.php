@extends('partials.chart')

@section('chart-stats')
    <div class="d-flex flex-column justify-content-end px-6 pb-4 fs-sm">
        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
            <div class="d-flex align-items-center w-1/3">
                <div class="flex-shrink-0 w-2 h-2 me-3 rounded-circle" style="background-color: #85CAC5"></div>
                <div class="text-truncate">Female</div>
            </div>

            <div class="w-1/3 fw-medium text-right">
                <span id="fC">{{ $genderStats['femaleCount'] }}</span> travellers
            </div>

            <div class="w-1/3 text-right text-secondary">
                <span id="fD">{{ $genderStats['femaleDiff'] }}</span>%
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between py-3">
            <div class="w-1/3 d-flex align-items-center">
                <div class="flex-shrink-0 w-2 h-2 me-3 rounded-circle" style="background-color: #5CB8B2"></div>
                <div class="text-truncate">Male</div>
            </div>

            <div class="w-1/3 fw-medium text-right">
                <span id="mC">{{ $genderStats['maleCount'] }}</span> travellers
            </div>

            <div class="w-1/3 text-right text-secondary">
                <span id="mD">{{ $genderStats['maleDiff'] }}</span>%
            </div>
        </div>
    </div>
@endsection

@push('chart-stats-js')
    <script defer>
      document.addEventListener('livewire:load', function () {
        let browserUrl = window.location.href.split('?').slice(1)[0];

        axios("{!! $chart->route($chartFilters) !!}" + `?${browserUrl}`, {
          method: 'get'
        }).then((response) => {

          const data = response.data.datasets[0].values;
          const female = data[0];
          const male = data[data.length - 1];

          document.getElementById('fC').innerHTML = Intl.NumberFormat().format(female);
          document.getElementById('mC').innerHTML = Intl.NumberFormat().format(male);

          document.getElementById('fD').innerHTML = Intl.NumberFormat().format((female / (female + male)) * 100);
          document.getElementById('mD').innerHTML = Intl.NumberFormat().format((male / (female + male)) * 100);

          // Emit livewire event
          Livewire.emit('genderStatsUpdated', {
            male, female
          });
        })
      });
    </script>
@endpush
