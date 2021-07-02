<template>
  <g-chart type="Table" :data="chartSeries" class="mb-3" />

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
  setup(props) {
    const chartSeries = Object.values(props.series).map((value, index, array) => {
      return [
        value.OfficialName, value.DocumentNumber, value.Surname, value.GivenName, value.Sex, value.DateOfBirth,
        value.BorderPoint, value.MovementDirection, value.TransportType, value.TravelDate
      ];
    });

    chartSeries.unshift(['Nationality', 'Document Number', 'Surname', 'Given Name', 'Sex', 'DOB',
      'Border Point', 'Direction', 'Transport Mode', 'Travel Date']);

    return { chartSeries };
  }
}
</script>
