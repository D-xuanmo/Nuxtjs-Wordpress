<template>
  <nuxt />
</template>

<script>
export default {
  name: 'blank',

  mounted() {
    document.body.classList.add('blank-page')
    this.$once('hook:beforeDestroy', () => {
      document.body.classList.remove('blank-page')
    })
  }
}
</script>
