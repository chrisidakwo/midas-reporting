<template>
  <app-card class="p-4" :loading="loading" style="height: 25rem !important;">
    <template #default>
      <div class="d-flex flex-column flex-fill h-100">
        <div class="d-flex flex-column" style="padding-bottom: 1rem">
          <div class="d-flex align-items-center justify-content-between">
            <div class="fw-bold text-secondary text-uppercase tracking-wider" style="font-size: var(--midas-font-size-xs)">
              Transportation Mode
            </div>

            <div class="actions">
              <button @click="updateDirection('entry')" class="px-3 py-1 mr-2 fs-sm" :class="{'bg-dark text-white rounded': transportDirection === 'entry'}">Entry</button>
              <button @click="updateDirection('exit')" class="px-3 py-1 fs-sm" :class="{'bg-dark text-white rounded': transportDirection === 'exit'}">Exit</button>
            </div>
          </div>

          <div class="mt-6 font-semibold text-2xl leading-tight" v-if="!loading">
            <span style="color: #5b92e5;">{{ highestMode }}</span> is the most used transportation mode
          </div>
        </div>

        <div class="d-flex flex-fill flex-column align-items-center justify-content-center">
          <apexchart class="w-100" type="bar" height="24px" :series="series" :options="options" />
        </div>

        <div class="d-flex flex-column justify-content-end fs-sm">
          <div class="d-flex align-items-center justify-content-between py-75 border-bottom last:border-bottom"
               v-for="(dataset, index) in series" :key="index">
            <div class="d-flex align-items-center w-1/3">
              <div class="flex-shrink-0 w-2 h-2 me-3 rounded-circle" :style="{ 'background-color': options.colors[index] }"></div>
              <div class="text-truncate">{{ series[index]['name'] }}</div>
            </div>

            <div class="w-1/3 fw-medium text-right">
              <span>{{ format(series[index]['data'][0]) }}</span> travellers
            </div>

            <div class="w-1/3 text-right text-secondary">
              <span>{{ format((dataset['data'][0] / totalTravellers) * 100) }}</span>%
            </div>
          </div>
        </div>
      </div>
    </template>
  </app-card>
</template>

<script>
import AppCard from '../Components/Card';
import { formatNumber } from '../utils/functions';

export default {
  components: {AppCard},
  props: ['loading', 'series', 'direction'],
  emits: ['directionUpdate'],
  data() {
    return {
      transportDirection: 'entry',
      options: {
        chart: {
          type: 'bar',
          stacked: true,
          stackType: '100%',
          height: '24px',
          foreColor: 'inherit',
          fontFamily: 'inherit',
          sparkline: {
            enabled: true
          },
          animations: {
            speed: 400,
            animateGradually: {
              enabled: true
            }
          }
        },
        colors: ['#5b92e5', '#84aeec', '#adc9f2'],
        plotOptions: {
          bar: {
            barHeight: '100%',
            horizontal: true
          }
        },
        // series: this.series,
        states: {
          hover: {
            filter: {
              type: 'none'
            }
          }
        },
        tooltip: {
          theme: 'dark',
          x: {
            show: false
          },
          custom: ({ seriesIndex, w }) => {
            const value = this.format(this.series[seriesIndex]['data'][0]);

            return `<div class="d-flex align-items-center h-8 min-h-8 max-h-8 px-3">
                      <div class="w-3 h-3 rounded-full" style="background-color: ${w.config.colors[seriesIndex]};"></div>
                      <div class="ml-2 text-md leading-none">${w.config.series[seriesIndex].name}:</div>
                      <div class="ml-2 text-md fw-bold leading-none">${value}</div>
                  </div>`;
          }
        },
        yaxis: {
          labels: {
            formatter: (val) => val.toString()
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
      this.transportDirection = direction;

      this.$emit('directionUpdate', direction);
    }
  },
  created() {
    this.transportDirection = this.direction;
  },
  computed: {
    totalTravellers() {
      return this.series.reduce((a, b) => {
        return a + b.data[0];
      }, 0);
    },

    highestMode() {
      return [...this.series].sort((a, b) => {
        return a.data[0] > b.data[0];
      })[this.series.length - 1].name;
    }
  },
}
</script>

<style scoped>

</style>
