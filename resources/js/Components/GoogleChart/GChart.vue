<template>
  <div class="google-chart">
    <div v-if="multiple" ref="chartWrap" v-for="(group, groupIndex) in data" :key="'chartGroup' + groupIndex">
      <div :class="formatClasses(group.classes)" class="chart-group">
        <div class="chart-group-name" v-if="group.name">{{ group.name }}</div>
        <div class="chart-wrap" v-for="(chart, index) in group.data" :key="'chart' + groupIndex + index">
          <div class="chart-name">{{ chart.name }}</div>
          <div :ref="chart.ref || 'chart' + groupIndex + '-' + index"></div>
        </div>
      </div>
    </div>
    <div v-else ref="chart"></div>
  </div>
</template>

<script>
import loadGoogleCharts from './lib/google-charts-loader';
let chartsLib = null
export default {
  name: 'GChart',
  props: {
    type: {
      type: String
    },
    data: {
      type: [Array, Object],
      default: () => []
    },
    options: {
      type: Object,
      default: () => ({})
    },
    version: {
      type: String,
      default: 'current'
    },
    settings: {
      type: Object,
      default: () => ({
        packages: ['corechart', 'table']
      })
    },
    events: {
      type: Object
    },
    createChart: {
      type: Function
    },
    multiple: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      chartStore: []
    }
  },
  watch: {
    data: {
      deep: true,
      handler () {
        this.redraw();
      }
    },
    options: {
      deep: true,
      handler () {
        this.redraw();
      }
    },
    type (value) {
      this.redraw();
    }
  },
  mounted () {
    loadGoogleCharts(this.version, this.settings).then(api => {
      chartsLib = api
      this.processChartData(api)
    })
  },
  methods: {
    processChartData (api) {
      if (this.multiple && Array.isArray(this.data)) {
        this.data.forEach((group, groupIndex) => {
          if (Array.isArray(group.data)) {
            group.data.forEach((chart, index) => {
              const ref = chart.ref || 'chart' + groupIndex + '-' + index
              const chartObj = this.createChartObject(ref)
              this.$emit('ready', chartObj, api)
              this.drawChart(chartObj, groupIndex, index, ref)
            })
          }
        })
      } else {
        const ref = 'chart'
        const chart = this.createChartObject(ref)
        this.$emit('ready', chart, api)
        this.drawChart(chart)
      }
    },
    drawChart (chart, groupIndex, index, ref) {
      if (!chartsLib || !this.chartStore.length > 0) return
      let data = null
      if (this.multiple) data = this.getSpecificValidChartData(groupIndex, index, ref)
      else data = this.getValidChartData()
      if (data) chart.draw(data, this.options)
    },
    redraw () {
      this.chartStore = []
      this.processChartData(chartsLib)
    },
    getValidChartData () {
      if (this.data instanceof chartsLib.visualization.DataTable) return this.data
      if (this.data instanceof chartsLib.visualization.DataView) return this.data
      if (Array.isArray(this.data)) return chartsLib.visualization.arrayToDataTable(this.data)
      if (this.data !== null && typeof this.data === 'object') return new chartsLib.visualization.DataTable(this.data)
      return null
    },
    getSpecificValidChartData (groupIndex, index, ref) {
      if (this.data[groupIndex].data[index].data instanceof chartsLib.visualization.DataTable) return this.data[groupIndex].data[index].data
      if (this.data[groupIndex].data[index].data instanceof chartsLib.visualization.DataView) return this.data[groupIndex].data[index].data
      if (Array.isArray(this.data[groupIndex].data[index].data)) return chartsLib.visualization.arrayToDataTable(this.data[groupIndex].data[index].data)
      if (this.data[groupIndex].data[index].data !== null && typeof this.data[groupIndex].data[index].data === 'object') return new chartsLib.visualization.DataTable(this.data[groupIndex].data[index].data)
      return null
    },
    createChartObject (ref) {
      const createChart = (el, google, type) => {
        if (!type) throw new Error('please, provide chart type property');

        return new google.visualization[type](el);
      }

      const fn = this.createChart || createChart
      let chart = {}
      if (this.multiple) chart = { [ref]: fn(this.$refs[ref][0], chartsLib, this.type) }
      else chart = { [ref]: fn(this.$refs[ref], chartsLib, this.type) }
      const chartIndex = this.chartStore.push(chart)
      this.attachListeners(chartIndex, ref)
      return this.chartStore[chartIndex - 1][ref]
    },
    attachListeners (index, ref) {
      if (!this.events) return
      Object.entries(this.events).forEach(([event, listener]) => {
        chartsLib.visualization.events.addListener(this.chartStore[index - 1][ref], event, listener);
      })
    },
    formatClasses (classes) {
      if (Array.isArray(classes)) return classes.join(' ');
    }
  }
}
</script>
