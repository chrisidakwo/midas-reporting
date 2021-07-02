<template>
  <g-chart type="Table" :data="series" :options="chartOptions" class="mb-3" />

  <div v-html="paginationLinks"></div>
</template>

<script>
import GChart from "../../GoogleChart/GChart";
export default {
  components: {GChart},
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    series: {
      type: Array,
      default: []
    },
    stats: {
      type: Object,
      default: {}
    },
    paginationLinks: {
      type: String,
      default: ''
    }
  },
  setup(props) {},
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
