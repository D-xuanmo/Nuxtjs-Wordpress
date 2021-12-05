<template>
  <footer :class="['footer', errorInformation.code && 'is-error', menuStatus && 'is-show-menu']">
    <div class="wrap">
      <div class="link-wrap" v-if="$route.name === 'index'">
        <a
          v-for="(item, index) in links"
          :key="index"
          :href="item.url"
          :target="item.target"
          :title="item.text"
        >
          {{ item.text }}
        </a>
      </div>
      <div class="copyright">
        <div class="left">
          <ul class="footer-menu">
            <li class="list" v-for="item in subMenu" :key="item.key">
              <nuxt-link
                v-if="item.object === 'category'"
                :to="{
                  name: 'category-id',
                  params: { id: 1 },
                  query: { type: item.object_id, title: item.title }
                }"
              >
                {{ item.title }}
              </nuxt-link>
              <nuxt-link
                v-else-if="item.object === 'page'"
                :to="{ name: 'page-id', params: { id: item.object_id } }"
              >
                {{ item.title }}
              </nuxt-link>
              <a v-else-if="item.object === 'custom'" :href="item.url">{{ item.title }}</a>
            </li>
            <li class="list">
              <nuxt-link to="/sitemap">站点地图</nuxt-link>
            </li>
          </ul>
          <!-- 版权文字 -->
          <div class="copyright-text" v-html="copyright"></div>
        </div>
        <p class="right">Theme by <a href="https://www.xuanmo.xin">Xuanmo</a></p>
      </div>
    </div>
    <div class="back-top" ref="backTop" @click="backTop" :class="{ show: isShowBackTop }">
      <x-icon class="icon-back-top"></x-icon>
    </div>
  </footer>
</template>
<script>
import { mapState } from 'vuex'
export default {
  name: 'AppFooter',
  data () {
    return {
      isShowBackTop: false
    }
  },
  computed: {
    ...mapState(['menuStatus', 'links', 'subMenu', 'errorInformation']),
    ...mapState({
      copyright: state => state.info.copyright
    })
  },
  mounted () {
    const self = this
    window.addEventListener('scroll', function () {
      self.isShowBackTop = this.scrollY > 300
    })
  },
  methods: {
    backTop () {
      window.scrollTo(0, 0)
    }
  }
}
</script>
<style lang="scss" scoped>
.footer {
  margin-top: $container-margin;
  padding: 30px 0;
  background: #2d3237;
  color: rgba(255, 255, 255, .45);
  transition: .5s;

  &.is-show-menu {
    transform: translateX(-240px);
  }

  &.is-error {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
  }

  ::v-deep a:not(.c-theme) {
    color: rgba(255, 255, 255, .45);

    &:hover {
      color: $color-theme;
    }
  }
}

.link-wrap {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid #3b424a;
  line-height: 2;

  &:after {
    content: "";
    flex: auto;
  }

  ::v-deep a {
    display: block;
    margin-right: $container-margin;
    font-size: $font-size-large;
  }
}

.copyright {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: $font-size-small;

  .footer-menu {
    display: flex;
    flex-wrap: wrap;

    .list {
      margin-right: 15px;
      line-height: 1.8;

      a {
        padding-right: 15px;
        border-right: 1px solid #666;
        font-size: $font-size-base;
      }

      &:last-child {
        a {
          border: 0;
        }
      }
    }
  }

  img {
    vertical-align: baseline;
  }

  .right {
    align-self: flex-end;
  }
}

.back-top {
  position: fixed;
  z-index: 99;
  right: 30px;
  bottom: 40px;
  transition: .5s;
  transform: translateX(100px);
  cursor: pointer;

  &.show {
    transform: translateX(0px);
  }

  .iconfont {
    font-size: 40px;
    color: $color-theme;
  }
}

@media screen and (max-width:1024px) {
  .copyright {
    flex-wrap: wrap;
  }

  .left {
    width: 100%;
  }

  .right {
    width: 100%;
    margin-top: $container-margin;
    text-align: right;
  }
}

@media screen and (max-width: 750px) {
  .link-wrap {
    display: flex;
    justify-content: flex-start;

    a {
      box-sizing: border-box;
      width: 32%;
      margin-right: 0;

      &:nth-of-type(3n+2) {
        width: 34%;
        padding: 0 10px;
      }
    }
  }
}
</style>
