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
    let chartSeries = Object.values(props.series).sort((a, b) => a[1] < b[1]);

    chartSeries.unshift(['Country', 'Total Entry', 'Total Exit']);

    chartSeries = chartSeries.slice(0, 15).filter((value) => value[0] != 'Nigeria');
    // chartSeries = chartSeries.slice(0, 15);

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
      console.log(google);

      return new google.charts.Bar(el);
      // return new google.visualization.ColumnChart(el);
    },

    onChartReady(chart, google) {
      this.chartsLib = google;
    }
  },
  computed: {
    chartOptions() {
      if (!this.chartsLib) return null;

      // return {
      //   chart: {
      //     title: 'Statistics By Nationality (Top 10 Countries)',
      //   },
      //   isStacked: true,
      //   colors: ['#0E9F6E', '#0033a0'],
      //   height: 2000,
      //   vAxis: {
      //     ticks: [0, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 7500, 8000, 8500, 9000, 9500, 10000, 10500, 11000, 11500, 12000, 12500, 13000, 13500, 14000]
      //   }
      // };

      return this.chartsLib.charts.Bar.convertOptions({
        chart: {
          title: 'Displaying only top 15 countries',
        },
        isStacked: true,
        colors: ['#0E9F6E', '#0033a0'],
        height: 500,
        bars: 'horizontal'
      })
    }
  }
}
</script>
