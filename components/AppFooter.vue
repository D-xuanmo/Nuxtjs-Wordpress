<template>
  <footer class="footer">
    <div class="wrap">
      <div class="link-wrap" v-html="$store.state.info.setExtend.link"></div>
      <div class="copyright">
        <div v-html="$store.state.info.setExtend.footer_copyright"></div>
        <p><a href="https://www.xuanmo.xin">Theme by Xuanmo</a></p>
      </div>
    </div>
  </footer>
</template>
<script>
import { mapGetters } from 'vuex'
export default {
  name: 'AppFooter',
  data () {
    return {

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
</style>
