<template>
  <g-chart type="Table" :data="chartSeries" class="mb-3" :options="chartOptions" />

  <div v-html="paginationLinks"></div>
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
    paginationLinks: {
      type: String,
      default: ''
    }
  },
  setup(props) {
    const chartSeries = Object.values(props.series).map((value, index, array) => {
      return [value[0], value[1], value[2], Number.parseInt(value[1]) + Number.parseInt(value[2])];
    }).sort((a, b) => a[0].toString().localeCompare(b[0]));

    chartSeries.unshift(['Country', 'Total Entry', 'Total Exit', 'Total Travellers']);

    return { chartSeries };
  },
  data() {
    return {
      chartOptions: {
        width: '100%',
        height: '100%'
      }
    }
  }
}
</script>
