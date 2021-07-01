<template>
  <app-layout>
    <template #breadcrumb>
      <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
      <span>Report</span>
    </template>

    <div class="row">
      <div class="col-12">
        <div class="px-4 my-4">
          <div class="bg-white shadow-sm">
            <div class="d-block w-full px-4 pt-4 mb-4">
              <h4 class="m-0">Search</h4>
            </div>

            <form>
              <div class="row px-4 justify-content-between">
                <div class="col-sm-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="ddlReportType">Report Type</label>
                    <select v-model="form.report_type" name="report_type" id="ddlReportType" class="form-select" required style="height: 40px;">
                      <option value=""></option>
                      <option value="1">Traveller Statistics</option>
                      <option value="2">Statistics By Nationality</option>
                      <option value="3">Movement Statistics</option>
                      <option value="4">Daily Movement Statistics</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4">
                  <label>Select Date</label>
                  <v-date-picker v-model="form.range" is-range style="margin-bottom: 1rem">
                    <template v-slot="{ inputValue, inputEvents }">
                      <div class="d-flex justify-content-center align-items-center">
                        <input :value="inputValue.start" v-on="inputEvents.start" name="start_date" class="form-control"/>
                        <svg class="w-4 h-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <input :value="inputValue.end" v-on="inputEvents.end" name="end_date" class="form-control"/>
                      </div>
                    </template>
                  </v-date-picker>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="ddlBorderPoint">Border Point</label>
                    <select v-model="form.border" name="border" id="ddlBorderPoint" class="form-select">
                      <option :value="border.OwnerID" v-for="(border, index) of borderPoints" :key="index">
                        {{ border.Name }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="mx-4 mb-4">
                    <button class="btn btn-primary" :click="generateReport" :disabled="loading">Get Report</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-12 col-lg-2 mb-4">
        <div class="ps-lg-4 pe-lg-0 px-4">
          <app-card :loading="loading" style="min-height: 250px;">
            <div class="header p-4">
              <div class="title fs-5" style="font-weight: 600">Statistics</div>
            </div>

            <div class="px-4">
              <div v-if="reportType == 1">
                Travellers Statistics
              </div>
            </div>
          </app-card>
        </div>
      </div>

      <div class="col-md-12 col-lg-10">
        <div class="pe-lg-4 ps-lg-0 px-4">
          <app-card :loading="loading" style="min-height: 450px;">
            <template #default>
              <div class="header p-4">
                <div class="title fs-5" style="font-weight: 600" v-if="reportType">{{ getReportType }} Report</div>
                <div class="title fs-5" style="font-weight: 600" v-else>Report Chart</div>
              </div>

              <div class="px-4">
                <div v-if="getReportType == 1">
                   Travellers statistics
                  <g-chart type="table" :data="series" />
                </div>
              </div>
            </template>
          </app-card>
        </div>
      </div>
    </div>

  </app-layout>
</template>

<script>
import {useForm} from '@inertiajs/inertia-vue3';
import AppLayout from "../Layouts/AppLayout";
import AppCard from "../Components/Card";
import GChart from "../Components/GoogleChart/GChart";

export default {
  components: {GChart, AppLayout, AppCard},
  props: {
    startDate: {
      type: String
    },
    endDate: {
      type: String
    },
    borderPoints: {
      type: Object
    },
    borderPoint: {
      type: String,
      default: null
    },
    reportType: null,
    series: {
      type: Array,
      default: []
    },
    stats: {
      type: Object,
      default: {}
    }
  },
  setup(props) {
    const form = useForm({
      range: {
        start: props.startDate,
        end: props.endDate
      },
      report_type: props.reportType ?? '',
      border: props.borderPoint ?? 282
    });

    return { form };
  },
  data() {
    return {
      loading: false
    }
  },
  methods: {
    generateReport() {
      if (!this.form.report_type) {
        alert('Please select a valid report type!');
        return;
      }

      this.loading = true;
    }
  },
  computed: {
    getReportType() {
      if (!this.reportType) return null;

      switch (this.reportType) {
        case '1':
        case 1:
          return 'Travellers\' Statistics';

        case '2':
        case 2:
          return 'Statistics By Nationality';

        case '3':
        case 3:
          return 'Movement Statistics';

        case '4':
        case 4:
          return 'Daily Movement Statistics';

      }
    }
  },
  created() {

  }
}
</script>

<style scoped>

</style>
