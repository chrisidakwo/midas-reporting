<template>
  <div class="wrapper">
    <app-header/>

    <main class="content">
      <app-breadcrumbs>
        <template #breadcumb>
          <slot name="breadcrumb"></slot>
        </template>
      </app-breadcrumbs>

      <div class="container-fluid">
        <slot></slot>
      </div>
    </main>

    <footer class="footer">IOM Nigeria © 2021</footer>
  </div>
</template>

<script>
import AppHeader from "../Components/Header";
import AppBreadcrumbs from "../Components/Breadcrumbs";

export default {
  name: "AppLayout",
  components: {AppBreadcrumbs, AppHeader}
}
</script>

<style scoped>

</style>
