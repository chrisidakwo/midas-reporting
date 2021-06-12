<template>
  <div class="row">
    <div class="col-12">
      <div class="px-4">
        <div class="d-flex flex-wrap w-100" style="column-gap: 2rem; row-gap: 1rem;">
          <app-card class="p-4" :loading="loading">
            <template #default>
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                  <div class="text-secondary tracking-wider text-uppercase fw-bold"
                       style="font-size: var(--midas-font-size-xs) !important;">Inbound Movements
                  </div>
                  <div style="color: #97A6BA; font-size: .75rem; font-weight: 500">Total number of inbound movements
                  </div>
                </div>
              </div>

              <div class="flex align-items-center mt-auto" style="min-height: 2.25rem !important;">
                <div class="d-flex flex-column">
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ series.inbound }}</div>
                </div>
              </div>
            </template>
          </app-card>

          <app-card class="p-4" :loading="loading">
            <template #default>
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                  <div class="text-secondary tracking-wider text-uppercase fw-bold"
                       style="font-size: var(--midas-font-size-xs) !important;">Outbound Movements
                  </div>
                  <div style="color: #97A6BA; font-size: .75rem; font-weight: 500">Total number of outbound movements
                  </div>
                </div>
              </div>

              <div class="flex align-items-center mt-auto" style="min-height: 2.25rem !important;">
                <div class="d-flex flex-column">
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ series.outbound }}</div>
                </div>
              </div>
            </template>
          </app-card>

          <app-card class="p-4" :loading="loading">
            <template #default>
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column">
                  <div class="text-secondary tracking-wider text-uppercase fw-bold"
                       style="font-size: var(--midas-font-size-xs) !important;">Alerted Persons
                  </div>
                  <div style="color: #97A6BA; font-size: .75rem; font-weight: 500">Total number of alerted person</div>
                </div>
              </div>

              <div class="flex align-items-center mt-auto" style="min-height: 2.25rem !important;">
                <div class="d-flex flex-column">
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ series.alerts }}</div>
                </div>
              </div>
            </template>
          </app-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AppCard from '../Components/Card';
import { formatNumber, buildStartEndDates } from '../utils/functions';

export default {
  components: {AppCard},
  props: ['series', 'loading'],
  computed: {
    inbound() {
      return formatNumber(this.series.inbound);
    },

    outbound() {
      return formatNumber(this.series.outbound);
    },

    alerted() {
      return formatNumber(this.series.alerts);
    }
  }
}
</script>

<style scoped>

</style>
