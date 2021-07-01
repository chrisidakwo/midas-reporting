<template>
  <div>
    <div class="row">
      <div class="col-lg-12 col-md-3 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Total Travellers</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_travellers) }}</div>
      </div>
      <div class="col-lg-12 col-md-3 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Total Entry</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_entry) }}</div>
      </div>
      <div class="col-lg-12 col-md-3 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Total Exit</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_exit) }}</div>
      </div>
      <div class="col-lg-12 col-md-3 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Male Travellers</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_male_travellers) }}</div>
      </div>
      <div class="col-lg-12 col-md-4 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Female Travellers</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_female_travellers) }}</div>
      </div>
      <div class="col-lg-12 col-md-4 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Total Air Travellers</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_air_travellers) }}</div>
      </div>
      <div class="col-lg-12 col-md-4 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Total Land Travellers</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_land_travellers) }}</div>
      </div>
      <div class="col-lg-12 col-md-4 col-sm-3 mb-3">
        <h6 class="m-0 fs-sm fw-medium text-secondary">Total Sea Travellers</h6>
        <div class="fs-4 fw-bold">{{ formatNumber(stats.total_sea_travellers) }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import {formatNumber} from "../../../utils/functions";

export default {
  props: {
    stats: {
      type: Object,
      default: {}
    }
  },
  methods: {
    formatNumber(number) {
      return formatNumber(number);
    }
  }
}
</script>

<style scoped>

</style>
