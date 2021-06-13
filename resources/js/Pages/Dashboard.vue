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

    <movement-statistics :series="movementSummary" :loading="movementLoading" />

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
            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <gender-chart :series="genderStats" :loading="demographicsLoading" />
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <transport-mode-chart :series="transportStats" :loading="demographicsLoading" />
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <age-group-chart :series="ageStats" :loading="demographicsLoading" />
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
import { ref } from 'vue';
import MovementStatistics from "../Components/MovementStatistics";
import GenderChart from "../Components/GenderChart";
import TransportModeChart from "../Components/TransportModeChart";
import AgeGroupChart from "../Components/AgeGroupChart";
import { formatNumber, buildStartEndDates } from '../utils/functions';
import { timer } from 'rxjs';
import { debounce } from 'rxjs/operators';

export default {
  components: {AgeGroupChart, TransportModeChart, GenderChart, MovementStatistics, AppLayout, AppCard},
  props: ['startDate', 'endDate'],

  setup(props) {
    const range = ref({
      start: props.startDate,
      end: props.endDate
    });

    return { range };
  },
  subscriptions() {
    this.$watchAsObservable('range').pipe(
        debounce(() => timer(2000))
    ).subscribe(res => {
      const dates = buildStartEndDates(res.newValue);

      const query = new URLSearchParams(window.location.search);
      query.set('start_date', dates[0]);
      query.set('end_date', dates[1]);

      window.history.pushState({}, document.title, window.location.href.split('?')[0] + '?' + query.toString());

      this.getData();
    })
  },
  data() {
    return {
      movementLoading: false,
      demographicsLoading: false,

      movementSummary: {
        inbound: 0,
        outbound: 0,
        alerts: 0
      },
      genderStats: [0, 0],

      ageStats: [
        { name: 'Under 10', data: [0]},
        { name: '11 to 17', data: [0]},
        { name: '18 to 40', data: [0]},
        { name: '41 to 65', data: [0]},
        { name: 'Above 65', data: [0]}
      ],

      transportStats: [
        {name: 'Air',  data: [0]},
        {name: 'Land',  data: [0]},
        {name: 'Sea',  data: [0]}
      ]
    }
  },
  methods: {
    dateUpdated(e) {
      this.range = e;
    },

    async getData() {
      this.movementLoading = true;
      this.demographicsLoading = true;

      const dates = buildStartEndDates(this.range);

      // Get movement summary
      let res = await this.axios.get(`/movement/summary?start_date=${dates[0]}&end_date=${dates[1]}`);
      if (res && res.status === 200) {
        this.movementSummary = {
          inbound: formatNumber(res.data.Inbound),
          outbound: formatNumber(res.data.Outbound),
          alerts: formatNumber(res.data.Alerts),
        };
      }

      this.movementLoading = false;

      // Load transport statistics to catch
      res = await this.axios.get(`/movement/demographics?start_date=${dates[0]}&end_date=${dates[1]}`);
      if (res && res.status === 200) {
        // Gender
        this.genderStats = res.data.gender;

        // Transport Mode
        this.transportStats = [
          { name: 'Air', data: [res.data.transport.Air] },
          { name: 'Land', data: [res.data.transport.Land ?? 0] },
          { name: 'Sea', data: [res.data.transport.Sea ?? 0] },
        ];

        // Age
        this.ageStats = [
          { name: 'Under 10', data: [res.data.age['10']]},
          { name: '11 to 17', data: [res.data.age['17']]},
          { name: '18 to 40', data: [res.data.age['40']]},
          { name: '41 to 65', data: [res.data.age['65']]},
          { name: 'Above 65', data: [res.data.age['65+']]}
        ];
      }

      this.demographicsLoading = false;
    }
  },

  mounted() {
    this.getData();
  }
}
</script>

<style scoped>

</style>
