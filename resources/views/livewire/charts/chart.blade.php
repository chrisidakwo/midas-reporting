@php
    /** @var \App\Charts\Chart $chart */
    $chartId = "chart_$wireId";
    $chartVariable = "chart$wireId";
@endphp

<x-dashboard-tile>
    <div id="{{$chartId}}" style="height: {{$height}}"></div>
</x-dashboard-tile>

@push('js')
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        let {{$chartVariable}} = new Chartisan({
          el: '#{{$chartId}}',
          url: "{!! $chart->route($chartFilters) !!}",
          hooks: new ChartisanHooks()
            .options({options: {!! json_encode($chart->options()) !!}})
            .datasets('{{$chart->type()}}')
            .colors({!! json_encode($chart->colors()) !!})
        });

          @if($refreshIntervalInSeconds > 0)
          setInterval(function () {
              {{$chartVariable}}.update({ background: true });
          }, {{$refreshIntervalInSeconds * 1000}})
          @endif
      });
    </script>
@endpush
