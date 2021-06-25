<template>
  <app-card class="p-4" :loading="loading" style="height: 25rem !important;">
    <template #default>
      <div class="d-flex flex-column flex-fill h-100">
        <div class="d-flex flex-column">
          <div class="mb-3 d-flex align-items-center justify-content-between">
            <div class="fw-bold text-secondary text-uppercase tracking-wider"
                 style="font-size: var(--midas-font-size-xs)">
              Gender
            </div>

            <div class="actions">
              <button @click="updateDirection('entry')" class="px-3 py-1 mr-2 fs-sm"
                      :class="{'bg-dark text-white rounded': genderDirection === 'entry'}">Entry
              </button>
              <button @click="updateDirection('exit')" class="px-3 py-1 fs-sm"
                      :class="{'bg-dark text-white rounded': genderDirection === 'exit'}">Exit
              </button>
            </div>
          </div>
        </div>

        <div class="d-flex flex-column flex-fill pb-6">
          <apexchart type="donut" height="190" :series="series" :options="options"/>
        </div>

        <div class="d-flex flex-column justify-content-end fs-sm">
          <div class="d-flex align-items-center justify-content-between py-75 border-bottom last:border-bottom"
               v-for="(dataset, index) in series" :key="index">
            <div class="d-flex align-items-center w-1/3">
              <div class="flex-shrink-0 w-2 h-2 me-3 rounded-circle"
                   :style="{ 'background-color': options.colors[index] }"></div>
              <div class="text-truncate">{{ labels[index] }}</div>
            </div>

            <div class="w-1/3 fw-medium text-right">
              <span>{{ format(series[index]) }}</span> travellers
            </div>

            <div class="w-1/3 text-right text-secondary">
              <span>{{ format((dataset / totalTravellers) * 100) }}</span>%
            </div>
          </div>
        </div>
      </div>
    </template>
  </app-card>
</template>

<script>
import AppCard from '../Components/Card';
import {formatNumber} from '../utils/functions';

export default {
  components: {AppCard},
  props: ['series', 'loading', 'direction'],
  emits: ['directionUpdate'],
  data() {
    return {
      genderDirection: 'entry',
      labels: ['Female', 'Male'],
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
          custom: ({series, seriesIndex, dataPointIndex, w}) => {
            const percentage = this.format((series[seriesIndex] / this.totalTravellers) * 100)

            return `<div class="d-flex align-items-center h-8 min-h-8 max-h-8 px-3">
                      <div class="w-3 h-3 rounded-full" style="background-color: ${w.config.colors[seriesIndex]};"></div>
                      <div class="ml-2 text-md leading-none">${w.config.labels[seriesIndex]}:</div>
                      <div class="ml-2 text-md fw-bold leading-none">${percentage}%</div>
                  </div>`;
          }
        }
      }
    }
  },
  methods: {
    format(value) {
      return formatNumber(value, 'US', {
        maximumFractionDigits: 2
      });
    },

    updateDirection(direction) {
      this.genderDirection = direction;

      this.$emit('directionUpdate', direction);
    }
  },
  created() {
    this.genderDirection = this.direction;
  },
  computed: {
    totalTravellers() {
      return this.series.reduce((a, b) => a + b, 0);
    }
  },
}
</script>

<style scoped>

</style>
