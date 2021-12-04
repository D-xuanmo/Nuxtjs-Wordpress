<template>
  <div v-show="isShow" class="reward-toast">
    <div class="dialog-model" @click="$emit('input', false)"></div>
    <div class="reward-toast-inner align-center">
      <x-icon type="icon-close" @click.native.stop="$emit('input', false)"></x-icon>
      <p class="thumbnail"><img :src="content.thumbnail" alt="" width="80"></p>
      <p class="summary">{{ content.text }}</p>
      <div class="reward-qrcode-wrap">
        <img v-if="reward" :src="content.alipay" class="reward-qrcode" width="150" height="150" alt="">
        <img v-else :src="content.wechatpay" class="reward-qrcode" width="150" height="150" alt="">
      </div>
      <div class="reward-btn">
        <el-radio v-model="reward" :label="true">
          <img src="../../assets/images/alipay.svg" width="80" alt="">
        </el-radio>
        <el-radio v-model="reward" :label="false">
          <img src="../../assets/images/wechatpay.svg" width="80" alt="">
        </el-radio>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Reward',
  data () {
    return {
      reward: true,
      isShow: false
    }
  },
  props: {
    content: {
      type: Object,
      default () {
        return {}
      }
    },
    value: {}
  },
  watch: {
    value (v) {
      this.isShow = v
    }
  }
}
</script>
<style lang="scss" scoped>
.reward-toast {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  width: 100%;
  height: 100%;

  .dialog-model {
    @extend %dialog-model;
  }

  img {
    margin-right: 0 !important;
  }

  .reward-toast-inner {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 400px;
    height: 400px;
    background: var(--color-sub-background);
    border-radius: 5px;
    transform: translate(-50%, -50%);
  }

  .icon-close {
    position: absolute;
    top: 15px;
    right: 15px;
    cursor: pointer;
  }

  .summary {
    font-size: 18px;
  }

  .thumbnail {
    padding: 30px 0 20px;

    img {
      border-radius: 50%;
    }
  }

  .reward-qrcode-wrap {
    margin: 15px 0;
  }

  .reward-btn {
    img {
      vertical-align: middle;
    }
  }
}
</style>
