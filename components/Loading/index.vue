<template>
  <div v-show="show" class="nuxt-loading-container">
    <div class="nuxt-progress" :style="{
      'width': percent+'%',
      'height': height,
      'background-color': canSuccess? color : failedColor,
      'opacity': show ? 1 : 0
    }">
    </div>
    <x-icon type="icon-loading"></x-icon>
    <p>{{ text }}</p>
  </div>
</template>

<script>
import Vue from 'vue'
export default {
  name: 'nuxt-loading',
  data () {
    return {
      percent: 0,
      show: false,
      canSuccess: true,
      duration: 5000,
      height: '',
      color: '',
      failedColor: '',
      text: '数据加载中...'
    }
  },
  methods: {
    start (text = '数据加载中...') {
      this.text = text
      this.show = true
      this.canSuccess = true
      if (this._timer) {
        clearInterval(this._timer)
        this.percent = 0
      }
      this._cut = 10000 / Math.floor(this.duration)
      this._timer = setInterval(() => {
        this.increase(this._cut * Math.random())
        if (this.percent > 95) {
          this.finish()
        }
      }, 100)
      return this
    },
    set (num) {
      this.show = true
      this.canSuccess = true
      this.percent = Math.floor(num)
      return this
    },
    get () {
      return Math.floor(this.percent)
    },
    increase (num) {
      this.percent = this.percent + Math.floor(num)
      return this
    },
    decrease (num) {
      this.percent = this.percent - Math.floor(num)
      return this
    },
    finish () {
      this.percent = 100
      this.hide()
      return this
    },
    pause () {
      clearInterval(this._timer)
      return this
    },
    hide () {
      clearInterval(this._timer)
      this._timer = null
      setTimeout(() => {
        this.show = false
        Vue.nextTick(() => {
          setTimeout(() => {
            this.percent = 0
          }, 200)
        })
      }, 500)
      return this
    },
    fail () {
      this.canSuccess = false
      return this
    }
  }
}
</script>

<style lang="scss">
.nuxt-loading-container {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 99999;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  background: rgba(255,255,255,.8);
  color: $color-theme;

  .icon-loading {
    font-size: 26px;
  }

  .nuxt-progress {
    position: fixed;
    top: 0px;
    left: 0px;
    right: 0px;
    height: 2px;
    width: 0%;
    transition: width 0.2s, opacity 0.4s;
    opacity: 1;
    background-color: $color-theme;
    z-index: 999999;
  }
}
</style>
