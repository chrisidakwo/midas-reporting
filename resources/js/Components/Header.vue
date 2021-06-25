<template>
  <header class="header">
    <inertia-link :href="route('dashboard')" class="logo">
      <img src="/img/brand/iom_icon_logo_blue.png" alt="" class="logo-text">
    </inertia-link>

    <nav class="navigation">
      <div class="navigation-wrapper">
<!--        <inertia-link :href="route('movement.index')" class="nav-menu-item">-->
<!--          <div class="nav-item-wrapper">-->
<!--            <div class="nav-item">-->
<!--              <i class="icon nav-item-icon material-icons-outlined icon-no-color">insights</i>-->
<!--              <span class="nav-item-title">Movement Statistics</span>-->
<!--            </div>-->
<!--          </div>-->
<!--        </inertia-link>-->

        <inertia-link :href="route('bcp.records')" class="nav-menu-item">
          <div class="nav-item-wrapper">
            <div class="nav-item">
              <i class="icon nav-item-icon material-icons-outlined icon-no-color">analytics</i>
              <span class="nav-item-title">BCP Records</span>
            </div>
          </div>
        </inertia-link>

        <inertia-link :href="route('visas')" class="nav-menu-item">
          <div class="nav-item-wrapper">
            <div class="nav-item">
              <i class="icon nav-item-icon material-icons-outlined icon-no-color">assignment_ind</i>
              <span class="nav-item-title">Visas</span>
            </div>
          </div>
        </inertia-link>

        <inertia-link :href="route('insights')" class="nav-menu-item">
          <div class="nav-item-wrapper">
            <div class="nav-item">
              <i class="icon nav-item-icon material-icons-outlined icon-no-color">insights</i>
              <span class="nav-item-title">Insights</span>
            </div>
          </div>
        </inertia-link>

        <inertia-link :href="route('reports')" class="nav-menu-item">
          <div class="nav-item-wrapper">
            <div class="nav-item">
              <i class="icon nav-item-icon material-icons-outlined icon-no-color">inventory_2</i>
              <span class="nav-item-title">Reports</span>
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
