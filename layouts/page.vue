<template>
  <div id="app">
    <app-header/>
    <div class="main">
      <div class="wrap">
        <div class="container">
          <nuxt/>
        </div>
      </div>
    </div>
    <app-footer/>
  </div>
</template>

<script>
import AppHeader from '~/components/AppHeader'
import AppFooter from '~/components/AppFooter'
export default {
  name: 'Page',
  components: {
    AppHeader,
    AppFooter
  },
}
</script>

<style lang="scss" scoped>
.wrap{
  display: flex;
  justify-content: space-between;
  margin-top: $container-margin;

  .container{
    width: 100%;
  }
}
</style>
