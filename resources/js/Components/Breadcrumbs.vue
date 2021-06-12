<template>
  <div class="breadcrumb bg-white border-bottom">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 d-flex align-items-center">
          <inertia-link class="lh-1 text-secondary me-1" :href="route('dashboard')">Dashboard</inertia-link>
          <slot name="breadcumb"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style scoped>

</style>
