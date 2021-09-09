<template>
  <i class="iconfont" :class="type"></i>
</template>
<script>
export default {
  name: 'XIcon',
  props: {
    type: {
      type: String,
      default: ''
    }
  }
}
</script>
