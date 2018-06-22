<template>
  <footer class="footer">
    <div class="wrap">
      <div class="link-wrap" v-html="$store.state.info.setExtend.link"></div>
      <div class="copyright">
        <div class="left" v-html="$store.state.info.setExtend.footer_copyright"></div>
        <p class="right">Theme by <a href="https://www.xuanmo.xin">Xuanmo</a></p>
      </div>
    </div>
    <div class="back-top" @click="backTop">
      <i class="iconfont icon-back-top"></i>
    </div>
  </footer>
</template>
<script>
import { mapGetters } from 'vuex'
export default {
  name: 'AppFooter',
  methods: {
    backTop () {
      window.scrollTo(0, 0)
    }
  }
}
</script>
<style lang="scss" scoped>
.footer{
  margin-top: $container-margin;
  padding: 30px 0;
  background: #2d3237;
}

.link-wrap{
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid #3b424a;
  line-height: 2;

  /deep/ a{
    display: inline-block;
    margin-right: $container-margin;
    font-size: $font-size-large;
    color: #9295a2;

    &:hover{
      color: $color-highlight-text;
    }
  }
}

.copyright{
  display: flex;
  justify-content: space-between;
  font-size: $font-size-small;

  img{
    vertical-align: baseline;
  }
}

.back-top{
  position: fixed;
  z-index: 99;
  right: 30px;
  bottom: 40px;

  .iconfont{
    font-size: 40px;
    color: $color-highlight-text;
  }
}

@media screen and (max-width:1024px) {
  .copyright{
    flex-wrap: wrap;
  }

  .left{
    width: 100%;
  }

  .right{
    width: 100%;
    margin-top: $container-margin;
    text-align: right;
  }
}
</style>
