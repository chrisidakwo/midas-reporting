<template>
  <app-card class="p-4" :loading="loading" style="height: 25rem !important;">
    <template #default>
      <div class="d-flex flex-column flex-fill h-100">
        <div class="d-flex flex-column" style="padding-bottom: 1rem">
          <div class="d-flex align-items-center justify-content-between">
            <div class="fw-bold text-secondary text-uppercase tracking-wider" style="font-size: var(--midas-font-size-xs)">
              Age Group
            </div>

            <div class="actions">
              <button @click="updateDirection('entry')" class="px-3 py-1 mr-2 fs-sm" :class="{'bg-dark text-white rounded': ageDirection === 'entry'}">Entry</button>
              <button @click="updateDirection('exit')" class="px-3 py-1 fs-sm" :class="{'bg-dark text-white rounded': ageDirection === 'exit'}">Exit</button>
            </div>
          </div>

          <div class="mt-6 font-semibold text-2xl leading-tight" v-if="!loading">
            <span style="color: #dd6c20;">{{ highestAge }}</span> years travelled the most
          </div>
        </div>

        <div class="d-flex flex-fill flex-column align-items-center justify-content-center w-full pb-6">
          <apexchart class="w-100" :series="series" :options="options" height="24" width="100%" />
        </div>

        <div class="d-flex flex-column justify-content-end text-md">
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
  props: ['series', 'loading', 'direction'],
  emits: ['directionUpdate'],
  data() {
    return {
      ageDirection: 'entry',
      options: {
        chart: {
          type: 'bar',
          stacked: true,
          stackType: '100%',
          height: '100%',
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
        colors: ['#dd6c20', '#f28a44', '#ff661f', '#ff8c57', '#ffc58f'],
        plotOptions: {
          bar: {
            barHeight: '100%',
            horizontal: true
          }
        },
        series: this.series,
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
  computed: {
    totalTravellers() {
      return this.series.reduce((a, b) => {
        return a + b.data[0];
      }, 0);
    },

    highestAge() {
      return [...this.series].sort((a, b) => {
        return a.data[0] > b.data[0];
      })[this.series.length - 1].name;
    }
  },
  methods: {
    format(value) {
      return formatNumber(value, 'US', {
        maximumFractionDigits: 2
      });
    },
    updateDirection(direction) {
      this.ageDirection = direction;

      this.$emit('directionUpdate', direction);
    }
  },
  created() {
    this.ageDirection = this.direction;
  },
}
</script>

<style scoped>

</style>
