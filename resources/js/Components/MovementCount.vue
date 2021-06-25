<template>
  <div class="mx-4">
    <div class="row mb-4">
      <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
        <app-card>
          <template #default>
            <div class="d-flex flex-column px-4 pt-4 pb-2" style="height:100%">
              <div class="d-flex w-100">
                <div class="d-flex align-items-center w-100 text-secondary fw-medium" style="font-size: var(--midas-font-size-xs) !important;">
                  <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem; height:1rem; margin-right:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="#0E9F6E">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                  </svg>

                  <span>Entries</span>
                </div>
              </div>

              <div class="d-flex flex-fill align-items-center justify-content-center">
                <div class="text-center my-3">
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ series.inbound }}</div>
                  <div class="text-center" style="color:#97A6BA;font-size:0.75rem;font-weight:500">
                    Total number of entries
                  </div>
                </div>
              </div>
            </div>
          </template>

          <template #footer>
            <div class="bg-success text-white text-center" style="height:20px"></div>
          </template>
        </app-card>
      </div>

      <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
        <app-card>
          <template #default>
            <div class="d-flex flex-column px-4 pt-4 pb-2" style="height:100%">
              <div class="d-flex w-100">
                <div class="d-flex align-items-center w-100 text-secondary fw-medium" style="font-size: var(--midas-font-size-xs) !important;">
                  <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem; height:1rem; margin-right:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="#9061F9">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>

                  <span>Exits</span>
                </div>
              </div>

              <div class="d-flex flex-fill align-items-center justify-content-center">
                <div class="text-center my-3">
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ series.outbound }}</div>
                  <div class="text-center" style="color:#97A6BA;font-size:0.75rem;font-weight:500">
                    Total number of exits
                  </div>
                </div>
              </div>
            </div>
          </template>

          <template #footer>
            <div class="bg-purple text-white text-center" style="height:20px"></div>
          </template>
        </app-card>
      </div>

      <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
        <app-card>
          <template #default>
            <div class="d-flex flex-column px-4 pt-4 pb-2" style="height:100%">
              <div class="d-flex w-100">
                <div class="d-flex align-items-center w-100 text-secondary fw-medium" style="font-size: var(--midas-font-size-xs) !important;">
                  <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem; height:1rem; margin-right:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="#FF5A1F">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                  </svg>

                  <span>Alerted Persons</span>
                </div>
              </div>

              <div class="d-flex flex-fill align-items-center justify-content-center">
                <div class="text-center my-3">
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ series.alerts }}</div>
                  <div class="text-center" style="color:#97A6BA;font-size:0.75rem;font-weight:500">
                    Total number of alerted persons
                  </div>
                </div>
              </div>
            </div>
          </template>

          <template #footer>
            <div class="bg-warning text-white text-center" style="height:20px"></div>
          </template>
        </app-card>
      </div>

      <div class="col-md-12 col-lg-3 mb-3">
        <app-card>
          <template #default>
            <div class="d-flex flex-column px-4 pt-4 pb-2" style="height:100%">
              <div class="d-flex w-100">
                <div class="d-flex align-items-center w-100 text-secondary fw-medium" style="font-size: var(--midas-font-size-xs) !important;">
                  <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem; height:1rem; margin-right:0.5rem;" fill="none" viewBox="0 0 24 24" stroke="#F05252">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                  </svg>

                  <span>Detained Persons</span>
                </div>
              </div>

              <div class="d-flex flex-fill align-items-center justify-content-center">
                <div class="text-center my-3">
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ series.denied }}</div>
                  <div class="text-center" style="color:#97A6BA;font-size:0.75rem;font-weight:500">
                    Total number of detained persons
                  </div>
                </div>
              </div>
            </div>
          </template>

          <template #footer>
            <div class="bg-danger text-white text-center" style="height:20px"></div>
          </template>
        </app-card>
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
      console.log('Inbound', this.series.inbound);
      return formatNumber(this.series.inbound);
    },

    outbound() {
      return formatNumber(this.series.outbound);
    },

    alerted() {
      return formatNumber(this.series.alerts);
    },

    denied() {
      return formatNumber(this.series.denied);
    }
  }
}
</script>

<style scoped>

</style>
