<template>
  <app-layout>
    <template #breadcrumb>
      <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
      <span>Home</span>
    </template>

    <div class="row justify-content-end">
      <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="p-4" style="z-index: 999">
          <v-date-picker v-model="range" is-range @drag="dateUpdated">
            <template v-slot="{ inputValue, inputEvents }">
              <div class="d-flex justify-content-center align-items-center">
                <input :value="inputValue.start" v-on="inputEvents.start" class="form-control"/>
                <svg class="w-4 h-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
                <input :value="inputValue.end" v-on="inputEvents.end" class="form-control"/>
              </div>
            </template>
          </v-date-picker>
        </div>
      </div>
    </div>

    <movement-statistics :range="range" />

    <div class="row mb-4">
      <div class="col-12">
        <div class="px-4">
          <div class="d-flex flex-column w-100 mb-3 mt-10">
            <h3 class="m-0">Travellers Demographic</h3>
            <div class="text-secondary tracking-tight">
              Demographic properties and general statistical characteristics of travellers and movements
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="px-4">
          <div class="row">
            <div class="col-sm-4">
              <div class="card d-flex flex-column flex-fill">
                <div class="card-body p-0">
                  <div class="overflow-hidden">
                    <gender-chart :range="range" />
<!--                    <livewire:gender-chart-tile title="Gender" chartClass="{{ \App\Charts\Demographics\GenderDemographicsChart::class }}" refreshIntervalInSeconds="100" />-->
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="card d-flex flex-column flex-fill">
                <div class="card-body p-0">
                  <div class="overflow-hidden">
<!--                    <livewire:chart-tile title="Transportation Mode" chartClass="{{ \App\Charts\Demographics\TransportDemographicsChart::class }}" refreshIntervalInSeconds="100" />-->
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="card d-flex flex-column flex-fill">
                <div class="card-body p-0">
                  <div class="overflow-hidden">
<!--                    <livewire:chart-tile chartClass="{{ \App\Charts\Demographics\GenderDemographicsChart::class }}" refreshIntervalInSeconds="10" />-->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "../Layouts/AppLayout";
import AppCard from '../Components/Card';
import { ref  } from 'vue';
import MovementStatistics from "../Components/MovementStatistics";
import GenderChart from "../Components/GenderChart";

export default {
  components: {GenderChart, MovementStatistics, AppLayout, AppCard},
  props: ['startDate', 'endDate'],

  setup(props) {
    const range = ref({
      start: props.startDate,
      end: props.endDate
    });

    return { range };
  },
  data() {
    return {

    }
  },
  methods: {
    dateUpdated(e) {
      // Todo, update location history. Remember there'll be other queries like border point, etc
      // window.history.pushState({}, document.title, window.location.href.split('?')[0] + `?start_date=${_startDate}&end_date=${_endDate}`);
      this.range = e;
    }
  },
}
</script>

<style scoped>

</style>
