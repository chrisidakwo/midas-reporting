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

              <div class="col">
                <div class="p-4">
                  <label for="ddlState">State</label>
                  <select name="state" id="ddlState" v-model="selectedState" class="form-control">
                    <option value="null" selected>Select a state</option>
                    <option :value="state.OwnerID" v-for="(state, index) in states" :key="index">
                      {{ state.Name }}
                    </option>
                  </select>
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

    <movement-count :series="movementSummary" :loading="movementLoading"/>

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
              <gender-chart @directionUpdate="updateGenderDirection" :direction="genderDirection" :series="genderStats"
                            :loading="genderDemographicsLoading"/>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <transport-mode-chart @directionUpdate="updateTransportDirection" :series="transportStats"
                                    :direction="transportModeDirection"
                                    :loading="transportModeDemographicsLoading"/>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
              <age-group-chart @directionUpdate="updateAgeDirection" :series="ageStats"
                               :direction="ageDirection"
                               :loading="ageGroupDemographicsLoading"/>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 col-lg-6 mb-4">
              <geo-chart title="Travel Destinations" :series="destinationsStat" :loading="destinationsLoading" :resizeDebounce="500" />
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
              <geo-chart title="Nationalities Entering Nigeria" :series="nationalitiesStat" :loading="nationalitiesLoading" :resizeDebounce="500" />
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
import MovementCount from "../Components/MovementCount";
import GenderChart from "../Components/GenderChart";
import TransportModeChart from "../Components/TransportModeChart";
import AgeGroupChart from "../Components/AgeGroupChart";
import {formatNumber, buildStartEndDates} from '../utils/functions';
import TrafficChart from "../Components/TrafficChart";
import Multiselect from '@suadelabs/vue3-multiselect';
import GeoChart from "../Components/GeoChart";

export default {
  components: {
    GeoChart,
    TrafficChart,
    AgeGroupChart,
    TransportModeChart,
    GenderChart,
    MovementCount,
    AppLayout,
    AppCard,
    Multiselect
  },
  props: ['startDate', 'endDate', 'states', 'selectedState'],

  setup(props) {
    const range = ref({
      start: props.startDate,
      end: props.endDate
    });

    return {range};
  },
  data() {
    return {
      // Loaders
      movementLoading: false,
      genderDemographicsLoading: false,
      ageGroupDemographicsLoading: false,
      transportModeDemographicsLoading: false,
      trafficLoading: false,
      destinationsLoading: false,
      nationalitiesLoading: false,

      // Flag variables
      selectedState: null,

      // Directions
      genderDirection: 'entry',
      transportModeDirection: 'entry',
      ageDirection: 'entry',

      // Data variables
      movementSummary: {
        inbound: 0,
        outbound: 0,
        alerts: 0,
        denied: 0
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
      trafficStates: [],

      destinationsStat: [],
      nationalitiesStat: []
    }
  },
  methods: {
    dateUpdated(e) {
      this.range = e;
    },

    clearAll() {
      this.selectedState = null;
    },

    updateGenderDirection(direction) {
      if (direction !== this.genderDirection) {
        this.genderDirection = direction;

        this.genderDemographicsLoading = true;

        // Get selected date range
        const dates = buildStartEndDates(this.range);

        // Retrieve data for gender based on new direction
        this.getGenderDemographicData(dates);

        this.genderDemographicsLoading = false;
      }
    },

    updateTransportDirection(direction) {
      if (direction !== this.transportModeDirection) {
        this.transportModeDirection = direction;

        // Get selected date range
        const dates = buildStartEndDates(this.range);

        this.transportModeDemographicsLoading = true;

        // Retrieve data for demographics based on new direction
        this.getTransportModeDemographicData(dates);

        this.transportModeDemographicsLoading = false;
      }
    },

    updateAgeDirection(direction) {
      if (direction !== this.ageDirection) {
        this.ageDirection = direction;

        // Get selected date range
        const dates = buildStartEndDates(this.range);

        this.ageDemographicsLoading = true;

        // Retrieve data for demographics based on new direction
        this.getAgeGroupDemographicData(dates);

        this.ageDemographicsLoading = false;
      }
    },

    async getGenderDemographicData(dates) {
      // Load gender statistics
      const res = await this.axios.get(`/movement/demographics/gender?start_date=${dates[0]}&end_date=${dates[1]}&direction=${this.genderDirection}`);
      if (res && res.status === 200) {
        this.genderStats = res.data;
      }
    },

    async getTransportModeDemographicData(dates) {
      // Load transport statistics to catch
      const res = await this.axios.get(`/movement/demographics/transport_mode?start_date=${dates[0]}&end_date=${dates[1]}&direction=${this.transportModeDirection}`);
      if (res && res.status === 200) {
        this.transportStats = [
          {name: 'Air', data: [res.data.Air]},
          {name: 'Land', data: [res.data.Land ?? 0]},
          {name: 'Sea', data: [res.data.Sea ?? 0]},
        ];
      }
    },

    async getAgeGroupDemographicData(dates) {
      // Load transport statistics to catch
      const res = await this.axios.get(`/movement/demographics/age?start_date=${dates[0]}&end_date=${dates[1]}&direction=${this.ageDirection}`);
      if (res && res.status === 200) {
        this.ageStats = [
          {name: 'Under 10', data: [res.data['10']]},
          {name: '11 to 17', data: [res.data['17']]},
          {name: '18 to 40', data: [res.data['40']]},
          {name: '41 to 65', data: [res.data['65']]},
          {name: 'Above 65', data: [res.data['65+']]}
        ];
      }
    },

    async getTravelDestinationDemographicData(dates) {
      // Load transport statistics to catch
      const res = await this.axios.get(`/movement/demographics/destination?start_date=${dates[0]}&end_date=${dates[1]}&direction=exit`);
      if (res && res.status === 200) {
        this.destinationsStat = res.data;
      }
    },

    async getMovementSummaryData(dates) {
      let res = await this.axios.get(`/movement/summary?start_date=${dates[0]}&end_date=${dates[1]}`);
      if (res && res.status == 200) {
        this.movementSummary = {
          inbound: formatNumber(res.data.Inbound),
          outbound: formatNumber(res.data.Outbound),
          alerts: formatNumber(res.data.Alerts),
          denied: formatNumber(res.data.Denied)
        };
      }
    },

    async getTrafficData(dates) {
      const res = await this.axios.get(`/movement/traffic?start_date=${dates[0]}&end_date=${dates[1]}`);
      if (res && res.status == 200) {
        this.trafficSeries = res.data.traffic;
        this.trafficStates = res.data.states;
      }
    },

    async getData() {
      this.movementLoading = true;
      this.genderDemographicsLoading = true;
      this.ageGroupDemographicsLoading = true;
      this.transportModeDemographicsLoading = true;
      this.trafficLoading = true;
      this.destinationsLoading = true;

      // Get selected date range
      const dates = buildStartEndDates(this.range);

      const query = new URLSearchParams(window.location.search);
      query.set('start_date', dates[0]);
      query.set('end_date', dates[1]);

      // Get selected state
      const state = this.selectedState ?? query.get('state') ?? null;
      query.delete('state');
      if (state) {
        query.set('state', state);
      }

      history.pushState({}, document.title, window.location.href.split('?')[0] + '?' + query.toString());

      // Get movement summary
      await this.getMovementSummaryData(dates);
      this.movementLoading = false;

      // Get gender demographics data
      await this.getGenderDemographicData(dates);
      this.genderDemographicsLoading = false;

      // Get transport mode demographics data
      await this.getTransportModeDemographicData(dates);
      this.transportModeDemographicsLoading = false;

      // Get age demographics data
      await this.getAgeGroupDemographicData(dates);
      this.ageGroupDemographicsLoading = false;

      await this.getTravelDestinationDemographicData(dates);
      this.destinationsLoading = false;

      // Get traffic data
      // await this.getTrafficData(dates);
      // this.trafficLoading = false;
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

.multiselect__tags, .multiselect__content-wrapper {
  border: 1px solid #97A6BA !important;
}
</style>
