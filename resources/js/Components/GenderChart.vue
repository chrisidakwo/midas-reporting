<template>
  <app-card class="p-4" :loading="loading">
    <template #default>
      <div class="d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between">
          <div class="fw-bold text-secondary text-uppercase tracking-wider" style="font-size: var(--midas-font-size-xs)">
            Gender
          </div>
        </div>
      </div>

      <div class="d-flex flex-column p-4 flex-fill pb-6" style="min-height: 200px">
        <apexchart type="donut" :series="dataSeries" :options="options" />
      </div>

      <div class="d-flex flex-column justify-content-end fs-sm">
        <div class="d-flex align-items-center justify-content-between py-3 border-bottom last:border-bottom"
             v-for="(dataset, index) in dataSeries" :key="index">
          <div class="d-flex align-items-center w-1/3">
            <div class="flex-shrink-0 w-2 h-2 me-3 rounded-circle" :style="{ 'background-color': options.colors[index] }"></div>
            <div class="text-truncate">{{ labels[index] }}</div>
          </div>

          <div class="w-1/3 fw-medium text-right">
            <span>{{ dataSeries[index] }}</span> travellers
          </div>

          <div class="w-1/3 text-right text-secondary">
            <span>{{ format((dataset / totalTravellers) * 100) }}</span>%
          </div>
        </div>
      </div>
    </template>
  </app-card>
</template>

<script>
import AppCard from '../Components/Card';
import { timer } from "rxjs";
import { debounce } from "rxjs/operators";
import { formatNumber, buildStartEndDates } from '../utils/functions';

export default {
  components: {AppCard},
  props: ['range'],
  subscriptions() {
    this.$watchAsObservable('range').pipe(
        debounce(() => timer(2000))
    ).subscribe(res => {
      this.getData();
    })
  },
  data() {
    return {
      loading: true,
      labels: ['Female', 'Male'],
      dataSeries: [0, 0],
      options: {
        chart: {
          type: 'donut',
          height: '100%',
          fontFamily: 'inherit',
          foreColor: 'inherit',
          sparkline: {
            enabled: true
          },
          animations: {
            speed: 400,
            animateGradually: {
              enabled: false
            }
          }
        },
        colors: ['#5cb8b2', '#85cac5'],
        labels: ['Female', 'Male'],
        plotOptions: {
          pie: {
            expandOnClick: false,
            donut: {
              size: '70%'
            }
          }
        },
        states: {
          hover: {
            filter: {
              type: 'none'
            }
          },
          active: {
            filter: {
              type: 'none'
            }
          }
        },
        tooltip: {
          enabled: true,
          fillSeriesColor: false,
          theme: 'dark',
          custom: ({ series, seriesIndex, dataPointIndex, w }) => {
            const percentage = this.format((series[seriesIndex] / this.totalTravellers) * 100)

            return `<div class="d-flex align-items-center h-8 min-h-8 max-h-8 px-3">
                      <div class="w-3 h-3 rounded-full" style="background-color: ${w.config.colors[seriesIndex]};"></div>
                      <div class="ml-2 text-md leading-none">${w.config.labels[seriesIndex]}:</div>
                      <div class="ml-2 text-md font-bold leading-none">${percentage}%</div>
                  </div>`;
          }
        }
      }
    }
  },
  methods: {
    format(value) {
      if (isNaN(value)) { value = 0; }

      return formatNumber(value, 'Us', {
        maximumFractionDigits: 2
      });
    },

    getData() {
      this.loading = true;

      const dates = buildStartEndDates(this.range);

      this.axios.get(`/movement/demographics/gender?start_date=${dates[0]}&end_date=${dates[1]}`)
      .then((res) => {
        this.loading = false;

        this.dataSeries = res.data;
      })
      .catch((err) => {
        this.loading = false;
      })
    }
  },

  computed: {
    totalTravellers() {
      return this.dataSeries.reduce((a, b) => a + b, 0);
    }
  },

  mounted() {
    this.getData();
  }
}
</script>

<style scoped>

</style>
