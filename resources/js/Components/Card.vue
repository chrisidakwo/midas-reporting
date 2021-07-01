<template>
<div class="card d-flex flex-column flex-fill position-relative justify-content-between" style="height:100%">
  <div :class="{ 'visually-hidden': !loading }" class="position-absolute h-100 w-100 top-0 left-0" style="background-color: rgba(241, 245, 249, 0.5); z-index:10;">
    <div class="w-100 d-flex flex-fill flex-column h-100 justify-content-center align-items-center position-relative text-center">
      <div class="spinner-border"></div>
    </div>
  </div>

  <div class="card-body p-0 overflow-hidden d-flex flex-column flex-fill">
    <slot></slot>
  </div>

  <slot name="footer"></slot>
  <slot name="extra"></slot>
</div>
</template>

<script>
export default {
  props: ['loading']
}
</script>

<style lang="scss">

</style>
