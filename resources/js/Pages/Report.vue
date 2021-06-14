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
            <div class="d-block w-full px-4 pt-4">
              <h4 class="m-0">Search</h4>
            </div>

            <div class="row justify-content-between">
              <div class="col">
                <div class="p-4" style="z-index: 9999">
                  <label>Select Date</label>
<!--                  <v-date-picker v-model="range" is-range @drag="dateUpdated">-->
<!--                    <template v-slot="{ inputValue, inputEvents }">-->
<!--                      <div class="d-flex justify-content-center align-items-center">-->
<!--                        <input :value="inputValue.start" v-on="inputEvents.start" class="form-control"/>-->
<!--                        <svg class="w-4 h-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                                d="M14 5l7 7m0 0l-7 7m7-7H3"/>-->
<!--                        </svg>-->
<!--                        <input :value="inputValue.end" v-on="inputEvents.end" class="form-control"/>-->
<!--                      </div>-->
<!--                    </template>-->
<!--                  </v-date-picker>-->
                </div>
              </div>

              <div class="col" style="z-index: 9999">
                <div class="p-4">
                  <label>Border Point</label>
<!--                  <multiselect v-model="selectedBorderPoints" :options="borderPoints" label="Name" track-by="OwnerID"-->
<!--                               placeholder="Type to search" open-direction="bottom" :searchable="true"-->
<!--                               :close-on-select="false" :clear-on-select="false" :preserve-search="true"-->
<!--                               :hide-selected="true" :limit="3" :limit-text="limitSelection"-->
<!--                               :loading="borderSearching">-->

<!--                    <template slot="clear" slot-scope="props">-->
<!--                      <div class="multiselect__clear" v-if="selectedBorderPoints.length"-->
<!--                           @mousedown.prevent.stop="clearAll(props.search)"></div>-->
<!--                    </template>-->
<!--                    <span slot="noResult">No border point found</span>-->
<!--                  </multiselect>-->
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="mx-4 mb-4">
                  <button class="btn btn-primary" @click="getData">Get Report</button>
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
export default {
  components: {AppLayout},
  methods: {
    getData() {

    }
  }
}
</script>

<style scoped>

</style>
