<template>
  <g-chart :data="chartSeries"
           :options="chartOptions"
           :settings="chartSettings"
           :create-chart="createChart"
           @ready="onChartReady"
           class="mb-3" />
</template>

<script>
import GChart from "../../GoogleChart/GChart";
export default {
  components: {GChart},
  props: {
    series: {
      type: Array,
      default: []
    },
  },
  setup(props) {
    const chartSeries = Object.values(props.series).slice(0, 16);

    return { chartSeries };
  },
  data() {
    return {
      chartsLib: null,
      chartSettings: {
        packages: ['corechart', 'bar']
      },
    }
  },
  methods: {
    createChart(el, google) {
      return new google.charts.Bar(el);
    },

    onChartReady(chart, google) {
      this.chartsLib = google;
    }
  },
  computed: {
    chartOptions() {
      if (!this.chartsLib) return null;

      return this.chartsLib.charts.Bar.convertOptions({
        chart: {
          title: 'Displaying only top 15 travel days',
        },
        isStacked: true,
        colors: ['#F05252', '#9061F9', '#FF5A1F', '#0E9F6E'],
        height: 700,
        bars: 'horizontal'
      })
    }
  }
}
</script>

<style scoped>

</style>
