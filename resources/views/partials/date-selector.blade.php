@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
@endpush

@php
    [$startDate, $endDate] = getDefaultStartEndDates();
@endphp

<div class="my-3">
    <div class="row justify-content-between align-items-center">
        <div class="col-sm-12 col-lg-7 mb-2 mb-sm-0">
            <div style="color: var(--midas-gray); font-size: var(--midas-font-size-sm)">The statistics displayed are for the days between <span id="start" class="fw-bold">{{ $startDate->isoFormat('Do MMM YYYY') }}</span>
                and
                <span id="end" class="fw-bold">{{ $endDate->isoFormat('Do MMM YYYY') }}</span>
            </div>
        </div>

        <div class="col-sm-12 col-lg-5">
            <div class="d-flex justify-content-sm-start justify-content-lg-end">
                <div id="dateRange"
                     class="bg-white d-flex align-items-center justify-content-between py-1 px-2 border-1"
                     style="min-width: 250px; max-width: 380px;">
                    <i class="icon material-icons-outlined"
                       style="width: 18px !important; height: 18px !important; font-size: 18px !important;">date_range</i>&nbsp;
                    <span class="text-secondary" style="font-size: var(--midas-font-size-sm)"></span> <i
                            class="icon material-icons-outlined"
                            style="width: 18px !important; height: 18px !important; font-size: 18px !important;">arrow_drop_down</i>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script>
      window.dateRange = {};

      $(function () {
        const dateRange = {};

        dateRange['startDate'] = moment('{{ $startDate->isoFormat('YYYY-MM-DD') }}');
        dateRange['endDate'] = moment('{{ $endDate->isoFormat('YYYY-MM-DD') }}');

        // Set date range to be global
        window.dateRange = dateRange;

        function cb(startDate, endDate) {
          // Update global dateRange values
          window.dateRange = {
            'startDate': startDate,
            'endDate': endDate
          };

          if (endDate.isAfter(moment())) {
            endDate = moment();
          }

          if (startDate.isAfter(endDate)) {
            startDate = endDate.clone().subtract(29, 'days');
          }

          $('#dateRange span').html(startDate.format('Do MMM YYYY') + ' - ' + endDate.format('Do MMM YYYY'));

          const _startDate = startDate.format('DD-MM-YYYY'), _endDate = endDate.format('DD-MM-YYYY');

          $('#start').html(startDate.format('Do MMM YYYY'));
          $('#end').html(endDate.format('Do MMM YYYY'));

          window.history.pushState({}, document.title, window.location.href.split('?')[0] + `?start_date=${_startDate}&end_date=${_endDate}`);
        }

        $('#dateRange').daterangepicker({
          startDate: dateRange.startDate,
          endDate: dateRange.endDate,
          locale: {
            applyLabel: 'Select Dates'
          },
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
        }, cb);

        cb(dateRange.startDate, dateRange.endDate);
      });
    </script>
@endpush
