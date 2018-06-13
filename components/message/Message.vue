<template>
  <transition name="msg-show">
    <div v-if="isShow" class="message clearfix" :class="[`alert-${type === undefined ? 'content' : type}`, { 'align-center': center }, { 'box-center': wrapCenter }]" :style="`width: ${width}px`">
      <p class="text">
        <i class="iconfont" :class="icon"></i> {{ msg }}
        <i v-if="showClose" class="fr iconfont icon-close" @click="leave"></i>
      </p>
      <p v-if="showImg && imgUrl" class="text-center msg-img">
        <img :src="imgUrl" width="100" height="100" alt="">
      </p>
    </div>
  </transition>
</template>
<script>
export default {
  name: 'message',
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
    leave (event) {
      this.isShow = false
    },

    // 显示消息
    show (opt, mark) {
      this.isShow = mark
      switch (typeof opt) {
        case 'string':
          this.msg = opt
          this.type = 'content'
          this.icon = 'icon-tips1'
          this.closeTime = 2000
          break
        case 'object':
          this.msg = opt.title
          this.type = opt.type
          this.center = opt.center
          this.wrapCenter = opt.wrapCenter
          this.closeTime = opt.time || 2000
          this.showClose = opt.showClose || false
          this.showImg = opt.showImg || false
          this.width = opt.width || 380
          this.imgUrl = opt.imgUrl
          switch (opt.type) {
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
          break
      }
      // 关闭消息提示
      !this.showClose && setTimeout(() => (this.isShow = false), this.closeTime)
    }
  }
}
</script>
<style lang="scss" scoped>
.message{
  position: fixed;
  top: 30px;
  left: 50%;
  z-index: 99999;
  box-sizing: border-box;
  width: 380px;
  padding: 10px 15px;
  border: 1px solid #ebeef5;
  border-radius: 5px;
  transition: .7s;
  transform: translateX(-50%);

  &.alert-success{
    border-color: #e1f3d8;
    background-color: #f0f9eb;
    color: #67c23a;
  }

  &.alert-content{
    background-color: #f4f4f5;
    color: #909399;
  }

  &.alert-warning{
    border-color: #faecd8;
    background-color: #fdf6ec;
    color: #e6a23c;
  }

  &.alert-error{
    border-color: #fde2e2;
    background-color: #fef0f0;
    color: #f56c6c;
  }

  &.align-center{
    text-align: center;
  }

  &.box-center{
    top: 50%;
    box-shadow: 0 0 20px #eee;
    transform: translate(-50%, -50%);
  }

  .msg-img{
    margin-top: 10px;
  }

  .iconfont{
    font-size: 16px;

    &.icon-close{
      font-size: 14px;
      cursor: pointer;
    }
  }
}
.msg-show-enter, .msg-show-leave-to{
  opacity: 0;
  transform: translateX(-50%) translateY(-100px);
}
@media screen and (max-width:360px) {
  .message{
    width: 240px;
    transform: translateX(-50%);
  }
}
</style>
