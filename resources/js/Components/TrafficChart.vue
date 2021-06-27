<template>
  <app-card class="p-4" :loading="loading">
    <template #default>
      <div class="d-flex flex-column w-100">
        <div class="d-flex align-items-center justify-content-between" style="padding-bottom: 1rem">
          <div class="fw-bold text-md text-secondary text-uppercase tracking-wider">Traffic By State</div>
        </div>

        <div class="d-flex flex-fill align-items-center w-100">
          <apexchart class="w-100" type="bar" :series="series" :options="chartOptions"/>
        </div>
      </div>
    </template>
  </app-card>
</template>

<script>
import AppCard from '../Components/Card';

export default {
  components: {AppCard},
  props: ['series', 'states', 'loading'],
  subscriptions() {
    this.$watchAsObservable('series').subscribe(res => {
      const newSeries = res.newValue;

      this.chartOptions = {
        ...this.chartOptions, ...{
          series: newSeries
        }
      }
    })

    this.$watchAsObservable('states').subscribe(res => {
      const states = res.newValue;

      this.chartOptions = {
        ...this.chartOptions, ...{
          xaxis: {
            categories: states
          }
        }
      }
    })
  },
  data() {
    return {
      selectedStates: [],
      chartOptions: {
        series: this.series,
        chart: {
          type: 'bar',
          height: '100%',
          stacked: true,
          foreColor: 'inherit',
          fontFamily: 'inherit',
          animations: {
            speed: 400,
            animateGradually: {
              enabled: true
            }
          }
        },
        colors: ['#5b92e5', '#ff8c57', '#5cb8b2'],
        stroke: {
          show: false
        },
        dataLabels: {
          enabled: false
        },
        plotOptions: {
          bar: {
            horizontal: false
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
          fillSeriesColor: false,
          theme: 'dark',
          x: {
            show: false
          },
        },
        xaxis: {
          type: 'category',
          categories: this.states
        },
        legend: {
          position: 'right',
          offsetY: 40
        },
        fill: {
          opacity: 1
        },
        yaxis: {
          show: true,
          showAlways: true,
          tickAmount: 10
        }
      }
    }
  }
}
</script>

<style scoped>

</style>
