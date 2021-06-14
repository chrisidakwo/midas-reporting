<template>
  <app-layout>
    <template #breadcrumb>
      <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
      <span>Home</span>
    </template>

    <div class="row">
      <div class="col-12">
        <div class="px-4 my-4">
          <div class="bg-white shadow-sm">
            <div class="d-block w-full px-4 pt-4">
              <h4 class="m-0">Filters</h4>
            </div>

            <div class="row justify-content-between">
              <div class="col">
                <div class="p-4" style="z-index: 9999">
                  <label>Select Date</label>
                  <v-date-picker v-model="range" is-range @drag="dateUpdated">
                    <template v-slot="{ inputValue, inputEvents }">
                      <div class="d-flex justify-content-center align-items-center">
                        <input :value="inputValue.start" v-on="inputEvents.start" class="form-control"/>
                        <svg class="w-4 h-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <input :value="inputValue.end" v-on="inputEvents.end" class="form-control"/>
                      </div>
                    </template>
                  </v-date-picker>
                </div>
              </div>

              <div class="col" style="z-index: 9999">
                <div class="p-4">
                  <label>Border Point</label>
                  <multiselect v-model="selectedBorderPoints" :options="borderPoints" label="Name" track-by="OwnerID"
                               placeholder="Type to search" open-direction="bottom" :searchable="true"
                               :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                               :hide-selected="true" :limit="3" :limit-text="limitSelection"
                               :loading="borderSearching">

                    <template slot="clear" slot-scope="props">
                      <div class="multiselect__clear" v-if="selectedBorderPoints.length"
                           @mousedown.prevent.stop="clearAll(props.search)"></div>
                    </template>
                    <span slot="noResult">No border point found</span>
                  </multiselect>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="mx-4 mb-4">
                  <button class="btn btn-primary" @click="getData">Apply Filters</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <movement-statistics :series="movementSummary" :loading="movementLoading"/>

    <div class="row mb-4">
      <div class="col-12">
        <div class="px-4">
          <div class="d-flex flex-column w-100 mb-3 mt-10">
            <h3 class="m-0">Travellers Demographic</h3>
            <div class="text-secondary tracking-tight">
              Demographic properties and general statistical characteristics of travellers and movements
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <gender-chart :series="genderStats" :loading="demographicsLoading"/>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <transport-mode-chart :series="transportStats" :loading="demographicsLoading"/>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <age-group-chart :series="ageStats" :loading="demographicsLoading"/>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-12">
        <div class="px-4">
          <traffic-chart :series="trafficSeries" :states="trafficStates" :loading="trafficLoading"/>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "../Layouts/AppLayout";
import AppCard from '../Components/Card';
import {ref} from 'vue';
import MovementStatistics from "../Components/MovementStatistics";
import GenderChart from "../Components/GenderChart";
import TransportModeChart from "../Components/TransportModeChart";
import AgeGroupChart from "../Components/AgeGroupChart";
import {formatNumber, buildStartEndDates} from '../utils/functions';
import TrafficChart from "../Components/TrafficChart";
import Multiselect from '@suadelabs/vue3-multiselect';

export default {
  components: {
    TrafficChart,
    AgeGroupChart,
    TransportModeChart,
    GenderChart,
    MovementStatistics,
    AppLayout,
    AppCard,
    Multiselect
  },
  props: ['startDate', 'endDate', 'borderPoints'],

  setup(props) {
    const range = ref({
      start: props.startDate,
      end: props.endDate
    });

    return {range};
  },
  data() {
    return {
      movementLoading: false,
      demographicsLoading: false,
      trafficLoading: false,

      selectedBorderPoints: [],
      borderSearching: false,

      movementSummary: {
        inbound: 0,
        outbound: 0,
        alerts: 0
      },
      genderStats: [0, 0],

      ageStats: [
        {name: 'Under 10', data: [0]},
        {name: '11 to 17', data: [0]},
        {name: '18 to 40', data: [0]},
        {name: '41 to 65', data: [0]},
        {name: 'Above 65', data: [0]}
      ],

      transportStats: [
        {name: 'Air', data: [0]},
        {name: 'Land', data: [0]},
        {name: 'Sea', data: [0]}
      ],

      trafficSeries: [],
      trafficStates: []
    }
  },
  methods: {
    dateUpdated(e) {
      this.range = e;
    },

    clearAll() {
      this.selectedBorderPoints = [];
    },

    limitSelection(count) {
      return `and ${count} other border points`
    },

    async findBorderPoint(query) {
      this.borderSearching = true;

      const res = await this.axios.get(`/border-points?search=${query}`);
      this.borderPoints = res.data;

      this.borderSearching = false;
    },

    async getData() {
      this.movementLoading = true;
      this.demographicsLoading = true;
      this.trafficLoading = true;

      // Get selected date range
      const dates = buildStartEndDates(this.range);

      const query = new URLSearchParams(window.location.search);
      query.set('start_date', dates[0]);
      query.set('end_date', dates[1]);

      // Get selected border points
      const borderPoint = this.selectedBorderPoints['OwnerID'] ?? query.get('border') ?? null;
      query.delete('border');
      if (borderPoint) {
        query.set('border', borderPoint);
      }

      console.log('Query', query.toString());
      console.log('Border Points', borderPoint);

      history.pushState({}, document.title, window.location.href.split('?')[0] + '?' + query.toString());

      // Get movement summary
      let res = await this.axios.get(`/movement/summary?start_date=${dates[0]}&end_date=${dates[1]}&border=${borderPoint}`);
      if (res && res.status == 200) {
        this.movementSummary = {
          inbound: formatNumber(res.data.Inbound),
          outbound: formatNumber(res.data.Outbound),
          alerts: formatNumber(res.data.Alerts),
        };
      }

      this.movementLoading = false;

      // Load transport statistics to catch
      res = await this.axios.get(`/movement/demographics?start_date=${dates[0]}&end_date=${dates[1]}&border=${borderPoint}`);
      if (res && res.status === 200) {
        // Gender
        this.genderStats = res.data.gender;

        // Transport Mode
        this.transportStats = [
          {name: 'Air', data: [res.data.transport.Air]},
          {name: 'Land', data: [res.data.transport.Land ?? 0]},
          {name: 'Sea', data: [res.data.transport.Sea ?? 0]},
        ];

        // Age
        this.ageStats = [
          {name: 'Under 10', data: [res.data.age['10']]},
          {name: '11 to 17', data: [res.data.age['17']]},
          {name: '18 to 40', data: [res.data.age['40']]},
          {name: '41 to 65', data: [res.data.age['65']]},
          {name: 'Above 65', data: [res.data.age['65+']]}
        ];
      }

      this.demographicsLoading = false;


      // Get traffic data
      res = await this.axios.get(`/movement/traffic?start_date=${dates[0]}&end_date=${dates[1]}&border=${borderPoint}`);
      if (res && res.status == 200) {
        this.series = res.data.traffic;
        this.states = res.data.states;
      }

      this.trafficLoading = false;
    }
  },

  mounted() {
    this.getData();
  }
}
</script>

<style lang="scss" scoped>
@import "~@suadelabs/vue3-multiselect/dist/vue3-multiselect.css";

.multiselect, .multiselect__option {
  min-height: 30px;
}

.multiselect__select {
  height: 32px;
}

.multiselect__tags {
  min-height: 35px !important;
}

.multiselect__input, .multiselect__single {
  margin-top: 5px;
}

.multiselect, .multiselect__input, .multiselect__single {
  font-size: 14px !important;
}
</style>
