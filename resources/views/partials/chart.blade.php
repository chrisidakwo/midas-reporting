@php
    /** @var Chart $chart */
    use App\Charts\Chart;$chartId = "chart_$wireId";
    $chartVariable = "chart$wireId"
@endphp

<div class="d-flex flex-column p-4">
    <div class="d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary text-uppercase tracking-wider" style="font-size: var(--midas-font-size-xs)">
            {{ $title }}
        </div>
    </div>
</div>

<div class="d-flex flex-column p-4 flex-fill pb-6">
    <div id="{{$chartId}}" style="height: {{$height}}"></div>
</div>

@yield('chart-stats')

<script defer>
  document.addEventListener("livewire:load", function () {
    let browserUrl = window.location.href.split('?').slice(1)[0];

    let {{$chartVariable}} = new Chartisan({
      el: '#{{$chartId}}',
      url: "{!! $chart->route($chartFilters) !!}" + `?${browserUrl}`,
      hooks: new ChartisanHooks()
        .options({options: {!! json_encode($chart->options()) !!}})
        .datasets('{{$chart->type()}}')
        .colors({!! json_encode($chart->colors()) !!})
    });

    Livewire.on('dateRangeUpdated', (dateRange) => {
      {{$chartVariable}}.destroy();

      {{$chartVariable}} = new Chartisan({
         el: '#{{$chartId}}',
         url: "{!! $chart->route($chartFilters) !!}" + `?${browserUrl}`,
         hooks: new ChartisanHooks()
           .options({options: {!! json_encode($chart->options()) !!}})
           .datasets('{{$chart->type()}}')
           .colors({!! json_encode($chart->colors()) !!})
      });
    });

    @if($refreshIntervalInSeconds > 0)
      setInterval(function () {
          {{$chartVariable}}.update({background: true});
      }, {{$refreshIntervalInSeconds * 1000}})
    @endif
  });
</script>

@stack('chart-stats-js')
