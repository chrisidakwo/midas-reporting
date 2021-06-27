require('./bootstrap');

// Import modules...
import {createApp, h} from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import {App as InertiaApp, plugin as InertiaPlugin} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import {ZiggyVue} from 'ziggy';
import {Ziggy} from './ziggy';
import route from 'ziggy-js';
import VCalendar from 'v-calendar';
import VueRx from "@nopr3d/vue-next-rx";
import VueAxios from 'vue-axios';
import NProgress from 'nprogress';
import VueApexCharts from "vue3-apexcharts";

const el = document.getElementById('app');

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el ,app, props, plugin }) {
    createApp({
      render: () => h(app, props),
    })
      .mixin({methods: {route}})
      .use(InertiaPlugin)
      .use(ZiggyVue, Ziggy)
      .use(VCalendar, {})
      .use(VueRx)
      .use(VueAxios, window.axios)
      .use(VueApexCharts)
      .mount(el);

  }
})

InertiaProgress.init({
  color: '#0033a0',
  showSpinner: true
});

window.axios.interceptors.request.use(function (config) {
  // Do something before request is sent
  NProgress.start();
  return config;
}, function (error) {
  // Do something with request error
  console.log(error);
  return Promise.reject(error);
});

// Add a response interceptor
window.axios.interceptors.response.use(function (response) {
  NProgress.done();
  return response;
}, function (error) {
  // Do something with response error
  console.log(error);
  return Promise.reject(error);
});
