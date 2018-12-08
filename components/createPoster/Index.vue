<template>
  <div class="create-poster">
    <div class="mask"></div>

  </div>
</template>
<script>
export default {
  name: 'CreatePoster',
  data () {
    return {

    }
  }
}
</script>
<style lang="scss" scoped>
.create-poster {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: $color-mask;
}
</style>
