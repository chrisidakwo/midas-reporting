<template>
  <app-card class="p-4 min-h-100" :loading="loading">
    <template #default>
      <div class="d-flex flex-column flex-fill">
        <div class="d-flex flex-column mb-3">
          <div class="d-flex align-items-center justify-content-between">
            <div class="fw-bold text-secondary text-uppercase tracking-wider"
                 style="font-size: var(--midas-font-size-xs)">
              {{ title }}
            </div>
          </div>
        </div>

        <div class="d-flex flex-column flex-fill pb-6">
          <template v-if="series && series.length > 0">
            <g-chart type="GeoChart" :data="series" :options="chartOptions" :settings="chartSettings"/>
          </template>

          <div v-else class="align-items-center d-flex justify-content-center mt-10">
            <p class="m-0 text-secondary">!!! Map could not be loaded !!!</p>
          </div>
        </div>
      </div>
    </template>
  </app-card>
</template>

<script>
import AppCard from '../Components/Card';
import GChart from "../Components/GoogleChart/GChart";

export default {
  components: {AppCard, GChart},
  props: {
    title: String,
    subtitle: String,
    series: {
      type: Array,
      default: []
    },
    loading: {
      type: Boolean,
      default: false
    },
    chartColors: {
      type: Array,
      default: ['#0033a0']
    }
  },
  data() {
    return {
      chartOptions: {
        chart: {
          title: this.title,
          subtitle: this.subtitle,
        },
        colorAxis: {
          minValue: 0,
          colors: this.chartColors
        }
      },
      chartSettings: {
        packages: ['geochart'],
        mapsApiKey: 'AIzaSyAgRTTYfIXZ3IlT3qGGIbpYQ4v0h3KlQbg'
      },
    }
  }
}
</script>
