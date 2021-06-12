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
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ inbound }}</div>
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
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ outbound }}</div>
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
                  <div class="fw-semibold text-5xl tracking-tighter lh-sm">{{ alerted }}</div>
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
import { timer } from "rxjs";
import { debounce } from "rxjs/operators";
import { formatNumber, buildStartEndDates } from '../utils/functions';

export default {
  components: {AppCard},
  props: ['range'],
  subscriptions() {
    this.$watchAsObservable('range').pipe(
        debounce(() => timer(2000))
    ).subscribe(res => {
      this.getData();
    })
  },
  data() {
    return {
      loading: false,
      inboundCount: 0,
      outboundCount: 0,
      alertedCount: 0
    }
  },
  methods: {
    getData() {
      this.loading = true;

      const dates = buildStartEndDates(this.range);

      this.axios.get(`/movement/summary?start_date=${dates[0]}&end_date=${dates[1]}`)
          .then((res) => {
            this.loading = false;

            this.inboundCount = res.data.Inbound;
            this.outboundCount = res.data.Outbound;
            this.alertedCount = res.data.Alerts;
          })
      .catch((err) => {
        this.loading = false;

        const statusCode = err.response.status;
        const errorMessage = err.response.statusText;
      })
    },
  },
  computed: {
    inbound() {
      return formatNumber(this.inboundCount);
    },

    outbound() {
      return formatNumber(this.outboundCount);
    },

    alerted() {
      return formatNumber(this.alertedCount);
    }
  },
  mounted() {
    this.getData();
  }
}
</script>

<style scoped>

</style>
