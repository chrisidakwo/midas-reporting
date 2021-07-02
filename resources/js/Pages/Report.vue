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

            <form @submit="generateReport" ref="searchForm">
              <div class="row px-4 justify-content-between">
                <div class="col-sm-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="ddlReportType">Report Type</label>
                    <select v-model="report_type" name="report_type" id="ddlReportType" class="form-select" required style="height: 40px;">
                      <option value=""></option>
                      <option value="1">Traveller Statistics</option>
                      <option value="2">Statistics By Nationality</option>
                      <option value="3">Daily Movement Statistics</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4">
                  <label>Select Date</label>
                  <v-date-picker v-model="range" is-range style="margin-bottom: 1rem">
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
                    <select v-model="border" name="border" id="ddlBorderPoint" class="form-select">
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
                    <button class="btn btn-primary me-2" type="submit" :disabled="loading">View Report</button>
                    <button class="btn btn-secondary text-white me-2" @click="downloadReport('statistics')" type="button" :disabled="loading">Download Statistical Report</button>
                    <button class="btn btn-secondary text-white me-2" @click="downloadReport('data')" type="button" :disabled="loading">Download Data Report</button>
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
          <app-card :loading="loading">
            <div class="header p-4">
              <div class="title fs-5" style="font-weight: 600">Statistics</div>
            </div>

            <div class="px-4 pb-4">
              <traveller-statistics-count :stats="stats" />
            </div>
          </app-card>
        </div>
      </div>

      <div class="col-md-12 col-lg-10">
        <div class="pe-lg-4 ps-lg-0 px-4">
          <app-card :loading="loading" style="min-height: 250px;">
            <template #default>
              <div class="header p-4">
                <div class="title fs-5" style="font-weight: 600" v-if="reportType">{{ getReportType }} Report</div>
                <div class="title fs-5" style="font-weight: 600" v-else>Report Chart</div>
              </div>

              <div class="px-4 pb-4">
                <div>
                  <traveller-statistics-table v-if="reportType == 1" :series="series" :stats="stats" :pagination-links="paginate" />

                  <div v-if="reportType == 2" class="mb-4 w-25">
                    <label for="ddlNationalityChartType" class="form-label">Display Data As:</label>
                    <select v-model="nationalityChartType" id="ddlNationalityChartType" class="form-select">
                      <option value="table">Table</option>
                      <option value="chart">Chart</option>
                    </select>
                  </div>
                  <nationality-statistics-table v-if="reportType == 2 && nationalityChartType == 'table'" :series="series" :pagination-links="paginate" />
                  <nationality-statistics-chart v-if="reportType == 2 && nationalityChartType == 'chart'" :series="series" />

                  <div v-if="reportType == 3" class="mb-4 w-25">
                    <label for="ddlDailyStatisticsChartType" class="form-label">Display Data As:</label>
                    <select v-model="dailyStatisticsChartType" id="ddlDailyStatisticsChartType" class="form-select">
                      <option value="table">Table</option>
                      <option value="chart">Chart</option>
                    </select>
                  </div>
                  <daily-movement-statistics-table v-if="reportType == 3 && dailyStatisticsChartType == 'table'" :series="series" />
                  <daily-movement-statistics-chart v-if="reportType == 3 && dailyStatisticsChartType == 'chart'" :series="series" />

                  <div v-if="!reportType" class="d-flex align-items-center justify-content-center mt-10">
                    Generate a report
                  </div>
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
import AppLayout from "../Layouts/AppLayout";
import AppCard from "../Components/Card";
import TravellerStatisticsTable from "../Components/Reports/TravellerStatistics/TravellerStatisticsTable";
import TravellerStatisticsCount from "../Components/Reports/TravellerStatistics/TravellerStatisticsCount";
import NationalityStatisticsTable from "../Components/Reports/NationalityStatistics/NationalityStatisticsTable";
import NationalityStatisticsChart from "../Components/Reports/NationalityStatistics/NationalityStatisticsChart";
import DailyMovementStatisticsTable from "../Components/Reports/DailyMovementStatistics/DailyMovementStatisticsTable";
import DailyMovementStatisticsChart from "../Components/Reports/DailyMovementStatistics/DailyMovementStatisticsChart";

export default {
  components: {
    DailyMovementStatisticsChart,
    DailyMovementStatisticsTable,
    NationalityStatisticsChart,
    NationalityStatisticsTable, TravellerStatisticsTable, AppLayout, AppCard, TravellerStatisticsCount},
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
    },
    paginate: {
      type: String,
      default: ''
    }
  },
  setup(props) {
    const range = {
      start: props.startDate,
      end: props.endDate
    };

    const report_type = props.reportType ?? '';
    const border = props.borderPoint ?? 282;

    return { range, report_type, border };
  },
  data() {
    return {
      loading: false,
      nationalityChartType: 'table',
      dailyStatisticsChartType: 'table',
    }
  },
  methods: {
    generateReport(e) {
      e.preventDefault();

      this.loading = true;

      if (!this.report_type) {
        alert('Please select a valid report type!');
        return;
      }

      e.currentTarget.submit();
    },

    downloadReport(type = 'statistics') {
      location.href = this.route('reports.download', {
        type: type,
        start_date: this.range.start,
        end_date: this.range.end,
        border: this.border,
        report_type: this.report_type
      });
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
