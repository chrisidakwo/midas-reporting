<template>
  <app-layout>
    <template #breadcrumb>
      <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
      <span>Visas</span>
    </template>

    <div class="px-4 my-4">
      <div class="row mb-3">
        <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
          <app-card class="bg-secondary p-4 text-white">
            <div class="d-flex flex-row align-items-center justify-content-between">
              <div>
                <h5>Visa Applications</h5>
                <h1 class="mb-0 fw-bold">13,478</h1>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" style="height:45px" fill="none" viewBox="0 0 24 24"
                     stroke="#fff">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>
          </app-card>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
          <app-card class="bg-warning p-4 text-white">
            <div class="d-flex flex-row align-items-center justify-content-between">
              <div>
                <h5>Visas Rejected</h5>
                <h1 class="mb-0 fw-bold">1,902</h1>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" style="height:45px" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
              </div>
            </div>
          </app-card>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
          <app-card class="bg-success p-4 text-white">
            <div class="d-flex flex-row align-items-center justify-content-between">
              <div>
                <h5>Visas Accepted</h5>
                <h1 class="mb-0 fw-bold">11,576</h1>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" style="height:45px" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
              </div>
            </div>
          </app-card>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
          <app-card class="bg-danger p-4 text-white">
            <div class="d-flex flex-row align-items-center justify-content-between">
              <div>
                <h5>Visa Overstays</h5>
                <h1 class="mb-0 fw-bold">97</h1>
              </div>

              <div>
                <svg xmlns="http://www.w3.org/2000/svg" style="height:45px" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
              </div>
            </div>
          </app-card>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-sm-12 col-md-6">
          <app-card class="p-4">
            <template #default>
              <div class="d-flex flex-column flex-fill h-100">
                <div class="d-flex flex-column">
                  <div class="mb-3 d-flex align-items-center justify-content-between">
                    <div class="fw-bold text-secondary text-uppercase tracking-wider"
                         style="font-size: var(--midas-font-size-xs)">
                      Types of Visa
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-fill pb-6">
                  <apexchart :series="visaTypesSeries" :options="visaTypeOptions" class="w-100" type="pie"
                             height="400"/>
                </div>
              </div>
            </template>
          </app-card>
        </div>

        <div class="col-sm-12 col-md-6">
          <app-card class="p-4">
            <template #default>
              <div class="d-flex flex-column flex-fill h-100">
                <div class="d-flex flex-column">
                  <div class="mb-3 d-flex align-items-center justify-content-between">
                    <div class="fw-bold text-secondary text-uppercase tracking-wider"
                         style="font-size: var(--midas-font-size-xs)">
                      Visa Applications Per Month
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-fill pb-6">
                  <apexchart :series="monthlyApplicationSeries" :options="monthlyApplicationOptions" class="w-100"
                             type="line"
                             height="400"/>
                </div>
              </div>
            </template>
          </app-card>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 col-md-6">
          <app-card class="p-4">
            <template #default>
              <div class="d-flex flex-column flex-fill h-100">
                <div class="d-flex flex-column">
                  <div class="mb-3 d-flex align-items-center justify-content-between">
                    <div class="fw-bold text-secondary text-uppercase tracking-wider"
                         style="font-size: var(--midas-font-size-xs)">
                      Age Group
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-fill pb-6">
                  <apexchart :series="ageGroupSeries" :options="ageGroupOptions" class="w-100" type="bar"
                             height="400"/>
                </div>
              </div>
            </template>
          </app-card>
        </div>

        <div class="col-sm-12 col-md-6">
          <app-card class="p-4">
            <template #default>
              <div class="d-flex flex-column flex-fill h-100">
                <div class="d-flex flex-column">
                  <div class="mb-3 d-flex align-items-center justify-content-between">
                    <div class="fw-bold text-secondary text-uppercase tracking-wider"
                         style="font-size: var(--midas-font-size-xs)">
                      Nationality of Applicants
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-fill pb-6">
                  <apexchart :series="applicantsSeries" :options="applicantsOptions" class="w-100" type="bar"
                             height="400"/>
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
import GChart from "../Components/GoogleChart/GChart";
import {formatNumber} from '../utils/functions';

export default {
  name: "Visas",
  components: {GChart, AppLayout, AppCard},
  setup() {

  },
  data() {
    // --midas-blue: #0033a0;
    // --midas-indigo: #6875F5;
    // --midas-purple: #9061F9;
    // --midas-pink: #E74694;
    // --midas-red: #F05252;
    // --midas-orange: #FF5A1F;
    // --midas-yellow: #C27803;
    // --midas-green: #0E9F6E;
    // --midas-teal: #0694A2;
    // --midas-cyan: #17a2b8;
    // --midas-white: #FFFFFF;
    // --midas-gray: #475569;
    // --midas-gray-dark: #27303F;
    // --midas-primary: #0033a0;
    // --midas-secondary: #64748B;
    // --midas-success: #0E9F6E;
    // --midas-info: #17a2b8;
    // --midas-warning: #FF5A1F;
    // --midas-danger: #F05252;
    // --midas-light: #f1f5f9;
    // --midas-dark: #364152;
    // --midas-purple: #9061F9;
    return {
      visaTypesSeries: [4190, 892, 1005, 2397, 3810, 1184],
      visaTypeOptions: {
        legend: {
          position: 'right',
          show: true
        },
        chart: {
          type: 'pie',
          height: '100%',
          fontFamily: 'inherit',
          foreColor: 'inherit',

          sparkline: {
            enabled: true
          },
          animations: {
            speed: 400,
            animateGradually: {
              enabled: false
            }
          }
        },
        colors: ['#0033a0', '#0E9F6E', '#FF5A1F', '#17a2b8', '#F05252', '#9061F9'],
        labels: ['Business Visa', 'Student Visa', 'Work Visa', 'Diplomatic Visa', 'Visa on Arrival', 'Transit Visa'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        plotOptions: {
          pie: {
            customScale: 1,
            expandOnClick: false,
          }
        },
        states: {
          hover: {
            filter: {
              type: 'none'
            }
          },
          active: {
            filter: {
              type: 'none'
            }
          }
        },
        tooltip: {
          enabled: true,
          fillSeriesColor: false,
          theme: 'dark',
          custom: ({series, seriesIndex, dataPointIndex, w}) => {
            const percentage = this.format((series[seriesIndex] / 13478) * 100)

            return `<div class="d-flex align-items-center h-8 min-h-8 max-h-8 px-3">
                      <div class="w-3 h-3 rounded-full" style="background-color: ${w.config.colors[seriesIndex]};"></div>
                      <div class="ml-2 text-md leading-none">${w.config.labels[seriesIndex]}:</div>
                      <div class="ml-2 text-md fw-bold leading-none">${percentage}%</div>
                  </div>`;
          }
        }
      },

      monthlyApplicationSeries: [
        {
          name: 'Total Applications',
          data: [578, 2902, 1093, 706, 1781, 1039, 1289, 1090, 894, 657, 1007, 442]
        },
        {
          name: 'Visa on Arrival',
          data: [200, 491, 311, 226, 486, 207, 322, 345, 320, 216, 189, 215]
        },
        {
          name: 'Diplomatic Visa',
          data: [83, 374, 194, 109, 338, 99, 205, 227, 212, 108, 90, 98]
        },
        {
          name: 'Business Visa',
          data: [231, 253, 455, 522, 342, 236, 353, 370, 350, 243, 220, 340]
        },
      ],
      monthlyApplicationOptions: {
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
          show: true
        },
        chart: {
          type: 'line',
          stacked: false,
          height: '100%',
          fontFamily: 'inherit',
          foreColor: 'inherit',
          animations: {
            speed: 400,
            animateGradually: {
              enabled: false
            }
          }
        },
        dataLabels: {
          enabled: false
        },
        colors: ['#0033a0', '#0E9F6E', '#FF5A1F', '#17a2b8', '#F05252', '#9061F9'],
        labels: ['Jan', 'Feb', 'Mar', 'Apr', "May", "Jun", 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        stroke: {
          width: [4, 4, 4, 4]
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        plotOptions: {
          bar: {
            customWidth: "40%"
          }
        },
        yaxis: {
          tickAmount: 20
        },
        states: {
          hover: {
            filter: {
              type: 'none'
            }
          },
          active: {
            filter: {
              type: 'none'
            }
          }
        },
        // tooltip: {
        //   enabled: true,
        //   fillSeriesColor: false,
        //   theme: 'dark',
        //   custom: ({series, seriesIndex, dataPointIndex, w}) => {
        //     const percentage = this.format((series[seriesIndex] / 13478) * 100)
        //
        //
        //     return `<div class="d-flex align-items-center h-8 min-h-8 max-h-8 px-3">
        //               <div class="w-3 h-3 rounded-full" style="background-color: ${w.config.colors[seriesIndex]};"></div>
        //               <div class="ml-2 text-md leading-none">${w.config.labels[seriesIndex]}:</div>
        //               <div class="ml-2 text-md fw-bold leading-none">${percentage}%</div>
        //           </div>`;
        //   }
        // }
      },

      ageGroupSeries: [
        {
          name: 'Male',
          data: [780, 938, 3901, 2783, 4805]
        },
        {
          name: 'Female',
          data: [-326, -871, -3782, -1890, -4639]
        }
      ],
      ageGroupOptions: {
        chart: {
          type: 'bar',
          height: 440,
          stacked: true
        },
        labels: ['Under 10', '11 to 17', '18 to 40', '41 to 65', 'Over 65'],
        colors: ['#FF5A1F', '#0E9F6E'],
        plotOptions: {
          bar: {
            horizontal: true,
            barHeight: '80%',
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: 1,
          colors: ["#fff"]
        },
        tooltip: {
          shared: false,
          x: {
            formatter: function (val) {
              return val
            }
          },
          y: {
            formatter: function (val) {
              return Math.abs(val)
            }
          }
        },
        xaxis: {
          labels: {
            formatter: function (val) {
              return Math.abs(val)
            }
          }
        },
      },


      applicantsSeries: [
        {
          data: [780, 938, 3901, 2783, 4805]
        }
      ],

      applicantsOptions: {
        chart: {
          type: 'bar',
          height: 400,
        },
        labels: ['Ghana', 'UAE', 'China', 'United Kingdom', 'USA'],
        colors: ['#17a2b8', '#FF5A1F'],
        dataLabels: {
          enabled: false
        },
        plotOptions: {
          bar: {
            horizontal: true,
          }
        },
        xaxis: {
          categories: ['Ghana', 'UAE', 'China', 'United Kingdom', 'USA']
        }
      }
    }
  },
  methods: {
    format(value) {
      return formatNumber(value, 'US', {
        maximumFractionDigits: 2
      });
    },
  },
  computed: {}
}
</script>

<style scoped>

</style>
