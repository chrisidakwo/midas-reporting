<template>
  <header class="header">
    <inertia-link :href="route('dashboard')" class="logo">
      <img src="/img/brand/iom_icon_logo_blue.png" alt="" class="logo-text">
    </inertia-link>

    <nav class="navigation">
      <div class="navigation-wrapper">
        <inertia-link :href="route('movement.index')" class="nav-menu-item">
          <div class="nav-item-wrapper">
            <div class="nav-item">
              <i class="icon nav-item-icon material-icons-outlined icon-no-color">insights</i>
              <span class="nav-item-title">Movement Statistics</span>
            </div>
          </div>
        </inertia-link>

        <inertia-link :href="route('reports')" class="nav-menu-item">
          <div class="nav-item-wrapper">
            <div class="nav-item">
              <i class="icon nav-item-icon material-icons-outlined icon-no-color">analytics</i>
              <span class="nav-item-title">Report</span>
            </div>
          </div>
        </inertia-link>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
}
</script>

<style scoped>

</style>
