<template>
  <svg class="iconfont-colour" aria-hidden="true">
    <use :xlink:href="`#${iconName}`"></use>
  </svg>
</template>
<script>
export default {
  name: 'SvgIcon',
  props: {
    iconName: String
  }
}
</script>
<style lang="scss" scoped>
.iconfont-colour {
  width: 20px;
  height: 20px;
}
</style>
