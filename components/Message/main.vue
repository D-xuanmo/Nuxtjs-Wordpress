<template>
  <transition name="msg-show">
    <div
      v-if="isShow"
      class="xm-message clearfix"
      :class="[
        `alert-${type === undefined ? 'content' : type}`,
        { 'align-center': center },
        { 'box-center': wrapCenter }
        ]"
    >
      <div class="xm-message-content" :style="`width: ${width}`">
        <p class="text">
          <x-icon :type="icon" class="vertical-middle" />
          <span class="vertical-middle">{{ msg }}</span>
          <x-icon v-if="showClose" type="icon-close" class="fr" @click.native="leave" />
        </p>
        <p v-if="showImg && imgUrl" class="align-center msg-img">
          <img :src="imgUrl" width="100" height="100" alt="">
        </p>
      </div>
    </div>
  </transition>
</template>
<script>
import XIcon from '../Icon/main.vue'

export default {
  name: 'message',
  components: { XIcon },
  data () {
    return {
      msg: '',
      icon: '',
      type: '',
      center: false,
      wrapCenter: '',
      isShow: false,
      showClose: false,
      showImg: false,
      closeTime: 0,
      width: 380,
      imgUrl: ''
    }
  },
  methods: {
    leave () {
      this.isShow = false
    },

    // 显示消息
    show ({
      title,
      type,
      center,
      wrapCenter,
      showImg,
      imgUrl,
      showClose,
      duration = 2000,
      width = 'initial'
    } = {}, mark) {
      this.isShow = mark
      this.msg = title
      this.type = type
      this.center = center
      this.wrapCenter = wrapCenter
      this.closeTime = duration
      this.showClose = showClose
      this.showImg = showImg
      this.width = width
      this.imgUrl = imgUrl
      switch (type) {
        case 'success':
          this.icon = 'icon-success-f'
          break
        case 'warning':
          this.icon = 'icon-info-f'
          break
        case 'error':
          this.icon = 'icon-close-f'
          break
        default:
          this.icon = 'icon-tips-f'
      }
      // 关闭消息提示
      this.closeTime && setTimeout(() => (this.isShow = false), this.closeTime)
    }
  }
}
</script>
<style lang="scss" scoped>
.xm-message {
  position: fixed;
  top: 30px;
  left: 50%;
  z-index: 2000;
  text-align: center;
  transition: .5s;
  transform: translateX(-50%);

  .xm-message-content {
    display: inline-block;
    box-sizing: border-box;
    width: 100%;
    padding: var(--small-gap) var(--base-gap);
    background: var(--color-sub-background);
    border-radius: 5px;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
  }

  &.alert-success {
    .iconfont{
      color: #52c41a;
    }
  }

  &.alert-content{
    .iconfont{
      color: #1890ff;
    }
  }

  &.alert-warning {
    .iconfont{
      color: #faad14;
    }
  }

  &.alert-error {
    .iconfont {
      color: #f5222d;
    }
  }

  &.align-center {
    text-align: center;
  }

  &.box-center {
    top: 50%;
    box-shadow: 0 0 20px #eee;
    transform: translate(-50%, -50%);
  }

  .msg-img {
    margin-top: var(--base-gap);
  }

  .iconfont {
    font-size: 16px;

    &.icon-close {
      margin-left: var(--base-gap);
      font-size: 14px;
      color: #ccc;
      cursor: pointer;
    }
  }
}
.msg-show-enter,
.msg-show-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(-100px);
}
</style>
