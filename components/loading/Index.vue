<template>
  <div v-if="loading" class="loading-wrap">
    <div class="loading-progress" :style="`width: ${percent}%; background: ${progressBackground};`"></div>
    <div class="loading-content">
      <x-icon type="icon-loading"></x-icon>
      <p class="loading-text">页面加载中</p>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Loading',
  data () {
    return {
      loading: false,
      throttle: 200,
      percent: 1,
      progressBackground: ''
    }
  },
  created () {
    this.progressBackground = this.$style['c-theme']
  },
  methods: {
    start () {
      this.loading = true
      const time = () => {
        this.timer = setTimeout(() => {
          this.increase(this.percent * Math.random())
          time()
          if (this.percent >= 90) {
            this.finish()
          }
        }, this.throttle)
      }
      time()
    },
    finish () {
      this.percent = 100
      setTimeout(() => {
        this.loading = false
        clearTimeout(this.timer)
        this.$nextTick(() => (this.percent = 1))
      }, this.throttle)
    },
    increase (num) {
      this.percent = this.percent + Math.ceil(num)
    },
    fail () {
      this.progressBackground = this.$style['c-error']
    }
  }
}
</script>
<style module>
.c-theme {
  background: $color-theme;
}

.c-error {
  background: $color-error;
}
</style>
<style lang="scss" scoped>
.loading-wrap {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 998;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  text-align: center;
  color: $color-theme;

  .iconfont {
    font-size: 30px;
  }

  .loading-progress {
    position: absolute;
    top: 0;
    left: 0;
    height: 3px;
    background: $color-theme;
  }
}
</style>
