@php
    /** @var Chart $chart */
    use App\Charts\Chart;$chartId = "chart_$wireId";
    $chartVariable = "chart$wireId"
@endphp


<div class="d-flex flex-column p-4">
    <div class="d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary text-uppercase tracking-wider" style="font-size: var(--midas-font-size-xs)">
            Gender
        </div>
    </div>
</div>

<div class="d-flex flex-column p-4 flex-fill pb-6">
    <div id="{{$chartId}}" style="height: {{$height}}"></div>
</div>

<div class="d-flex flex-column justify-content-end px-6 pb-4 fs-sm">
    <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
        <div class="d-flex align-items-center w-1/3">
            <div class="flex-shrink-0 w-2 h-2 me-3 rounded-circle" style="background-color: #85CAC5"></div>
            <div class="text-truncate">Female</div>
        </div>

        <div class="w-1/3 fw-medium text-right">
            {{ $femaleCount }} travellers
        </div>

        <div class="w-1/3 text-right text-secondary">
            {{ $femaleDifference }}%
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between py-3">
        <div class="w-1/3 d-flex align-items-center">
            <div class="flex-shrink-0 w-2 h-2 me-3 rounded-circle" style="background-color: #5CB8B2"></div>
            <div class="text-truncate">Male</div>
        </div>

        <div class="w-1/3 fw-medium text-right">
            {{ $maleCount }} travellers
        </div>

        <div class="w-1/3 text-right text-secondary">
            {{ $maleDifference }}%
        </div>
    </div>
</div>


<script defer>
  document.addEventListener("livewire:load", function () {
    let browserUrl = window.location.href.split('?').slice(1)[0];

    let {{$chartVariable}} =
    new Chartisan({
      el: '#{{$chartId}}',
      url: "{!! $chart->route($chartFilters) !!}" + `?${browserUrl}`,
      hooks: new ChartisanHooks()
        .options({options: {!! json_encode($chart->options()) !!}})
        .datasets('{{$chart->type()}}')
        .colors({!! json_encode($chart->colors()) !!})
    });

      @if($refreshIntervalInSeconds > 0)
      setInterval(function () {
          {{$chartVariable}}.update({background: true});

        {{--axios("{!! $chart->route($chartFilters) !!}" + `?${browserUrl}`, {--}}
        {{--  method: 'get'--}}
        {{--}).then((response) => {--}}

        {{--  const data = response.data.datasets[0].values;--}}
        {{--  const female = data[0];--}}
        {{--  const male = data[data.length - 1];--}}

        {{--@this.femaleCount = Intl.NumberFormat('GB').format(female);--}}
        {{--@this.maleCount = Intl.NumberFormat('GB').format(male);--}}

        {{--@this.femaleDifference = (female / (female + male)) * 100--}}
        {{--@this.maleDifference = (male / (female + male)) * 100--}}
        {{--})--}}
      }, {{$refreshIntervalInSeconds * 1000}})
      @endif
  });
</script>
