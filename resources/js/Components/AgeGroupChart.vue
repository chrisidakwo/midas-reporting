<template>
  <app-card class="p-4" :loading="loading">
    <template #default>
      <div class="d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between">
          <div class="fw-bold text-secondary text-uppercase tracking-wider" style="font-size: var(--midas-font-size-xs)">
            Age Group
          </div>
        </div>
      </div>

      <div class="d-flex flex-column p-4 flex-fill pb-6" style="min-height: 200px">

      </div>
    </template>
  </app-card>
</template>

<script>
import AppCard from '../Components/Card';

export default {
  components: {AppCard},
  props: ['range'],
  subscriptions() {

  },
  data() {
    return {
      loading: false
    }
  }
}
</script>

<style scoped>

</style>
