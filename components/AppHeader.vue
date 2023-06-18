<template>
  <header :class="['header-container', menuStatus && 'is-show-menu']">
    <div class="hide-header"></div>
    <div class="header-content" ref="header">
      <div class="wrap h-f-100">
        <!-- logo 开始 -->
        <div class="logo">
          <h1>{{ globalConfig.blogName }}</h1>
          <nuxt-link :to="{ name: 'index' }" class="block">
            <img :src="globalConfig.logo" class="vertical-middle" width="120" height="40">
          </nuxt-link>
        </div>
        <!-- logo结束 -->
        <!-- 导航开始 -->
        <div :class="['nav-wrapper', 'h-f-100', menuStatus && 'is-show']">
          <ul class="nav-view h-f-100">
            <li class="nav-item h-f-100" v-for="item in menu" :key="item.key">
              <nuxt-link
                v-if="item.object === 'category'"
                :to="{
                  name: `${item.object}-id`,
                  params: { id: 1 },
                  query: { type: item.object_id, title: item.title }
                }"
                :class="{
                  'first-link': true,
                  'nuxt-link-exact-active': $route.query.type === item.object_id
                }"
              >
                <x-icon :type="item.classes" /> {{ item.title }}
                <x-icon v-if="item.children.length !== 0" type="icon-arrow-bottom" />
              </nuxt-link>
              <nuxt-link
                v-else-if="item.object === 'page'"
                :to="{ name: 'page-id', params: { id: item.object_id } }"
                class="first-link">
                <x-icon :type="item.classes" /> {{ item.title }}
              </nuxt-link>
              <nuxt-link
                v-else-if="item.object === 'post_tag'"
                :to="{ name: 'tags-id', params: { id: 1 }, query: { type: item.term_id, title: item.name } }"
                class="first-link">
                <x-icon :type="item.classes" /> {{ item.title }}
              </nuxt-link>
              <a
                v-else-if="item.object === 'custom'"
                :href="item.url"
                :class="{
                  'first-link': true,
                  'nuxt-link-exact-active': $route.path === item.url
                }"
              >
                <x-icon :type="item.classes" /> {{ item.title }}
              </a>
              <!-- 二级菜单 -->
              <div v-if="item.children.length !== 0" class="sub-nav-wrapper">
                <ul class="sub-nav-view">
                  <li v-for="child in item.children" :key="child.key" class="sub-item">
                    <nuxt-link
                      v-if="child.object === 'category'"
                      :to="{
                        name: 'category-id',
                        params: { id: 1 },
                        query: { type: child.object_id, title: child.title }
                      }"
                    >
                      <x-icon :type="child.classes" /> {{ child.title }}
                    </nuxt-link>
                    <nuxt-link
                      v-else-if="child.object === 'page'"
                      :to="{
                        name: 'page-id',
                        params: { id: child.object_id }
                      }"
                    >
                      <x-icon :type="child.classes" /> {{ child.title }}
                    </nuxt-link>
                    <nuxt-link
                      v-else-if="child.object === 'post_tag'"
                      :to="{ name: 'tags-id', params: { id: 1 }, query: { type: child.object_id, title: child.title } }">
                      <x-icon :type="child.classes" /> {{ child.title }}
                    </nuxt-link>
                    <a v-else-if="child.object === 'custom'" :href="child.url">
                      <x-icon :type="child.classes" /> {{ child.title }}
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
        <!-- 导航结束 -->
        <!-- 右侧搜索开始 -->
        <div class="controller">
          <client-only>
            <span
              v-if="theme === 'dark'"
              class="theme-switch--handler"
              @click="_switchTheme('light')"
            >🌝</span>
            <span
              v-if="theme === 'light'"
              class="theme-switch--handler"
              @click="_switchTheme('dark')"
            >🌚</span>
          </client-only>
          <div :class="['search-wrapper', isShowSearch && 'is-show']">
            <div class="search-content">
              <input
                v-model="searchText"
                type="text"
                placeholder="请输入关键字"
                @keyup.enter="_search"
              >
              <x-icon type="icon-search" @click.native="_search" />
              <x-icon type="icon-close hide"  @click.native="isShowSearch = false" />
            </div>
          </div>
          <x-icon type="icon-search tablet-show" @click.native="isShowSearch = true" />
          <x-icon type="icon-menu hide" @click.native="_showNavWrap" />
        </div>
        <!-- 右侧搜索结束 -->
      </div>
    </div>
  </header>
</template>
<script>
import { mapState } from 'vuex'
import $ from '@/utils/operationDOM'
import XIcon from './Icon/main.vue'
export default {
  components: { XIcon },
  watchQuery: ['type'],
  name: 'AppHeader',
  data () {
    return {
      searchText: '',
      isShowSearch: false,
      mark: true,
      theme: 'light'
    }
  },
  head () {
    return {
      link: [
        { rel: 'icon', type: 'image/x-icon', href: this.globalConfig.favicon }
      ],
      style: [
        { cssText: this.globalConfig.globalCss, type: 'text/css' }
      ]
    }
  },
  computed: {
    ...mapState(['globalConfig', 'menu', 'menuStatus']),
    height () {
      return this.menuStatus ? `${window.innerHeight}px` : '100%'
    }
  },
  watch: {
    menuStatus (v) {
      this.mark = !v
      $('body').css('height', this.height)
      v ? $('body').addClass('h-f-100') : $('body').removeClass('h-f-100')
    }
  },
  mounted() {
    this.theme = localStorage.getItem('x-theme') || 'light'
    document.body.setAttribute('data-theme', this.theme)
  },
  methods: {
    // 搜索
    _search () {
      this.$router.push({
        name: 'search',
        query: {
          page: 1,
          s: this.searchText
        }
      })
      this.searchText = ''
      this.isShowSearch = false
    },

    // 显示菜单
    _showNavWrap () {
      this.$store.commit('UPDATE_MENU_STATUS', this.mark)
      this.mark = !this.mark
    },

    _closeMenu () {
      this.$store.commit('UPDATE_MENU_STATUS', false)
    },

    _switchTheme(mode) {
      this.theme = mode
      localStorage.setItem('x-theme', mode)
      document.body.setAttribute('data-theme', mode)
    }
  }
}
</script>
<style lang="scss" scoped>
$headerHeight: 60px;
.header-container {
  position: relative;
  width: 100%;
  height: $headerHeight;
  background: var(--color-sub-background);

  .hide-header {
    height: $headerHeight;
  }

  .header-content {
    position: fixed;
    top: 0;
    left: 0;
    z-index: $z-index + 999;
    width: 100%;
    height: 60px;
    background: var(--color-sub-background);
    box-shadow: $box-shadow;
  }

  .wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .logo {
    h1 {
      position: fixed;
      top: -500%;
      left: 0;
    }
  }

  // 导航
  .nav-wrapper {
    flex: 1;
    margin: 0 20px;
    .nav-view {
      display: flex;
    }

    .nav-item {
      position: relative;
      .first-link {
        display: block;
        padding: 0 10px;
        line-height: $headerHeight;
      }

      &:hover {
        .sub-nav-wrapper {
          display: block;
        }
      }
    }
  }

  .controller {
    display: flex;
    align-items: center;

    .theme-switch--handler {
      margin-right: 10px;
      font-size: 20px;
      cursor: pointer;
    }
  }

  // 搜索框
  .search-wrapper {
    width: 200px;
    overflow: hidden;

    .search-content {
      display: flex;
      align-items: center;
      border: 1px solid var(--color-border);
      border-radius: $border-radius;
    }

    input[type="text"] {
      -webkit-appearance: none;
      width: 160px;
      height: 30px;
      padding: 0 5px;
    }

    .iconfont {
      width: 30px;
      height: 30px;
      background: $color-main-background;
      text-align: center;
      line-height: 30px;
      color: $color-primary;
      cursor: pointer;
    }

    .icon-search {
      border-radius: 0 $border-radius $border-radius 0;
    }
  }
}

@media screen and (min-width:1024px) {
  .header-container {
    .sub-nav-wrapper {
      display: none;
      position: absolute;
      top: 100%;
      left: 50%;
      z-index: $z-index;
      width: 180px;
      transform: translateX(-50%);
    }

    .sub-nav-view {
      position: relative;
      top: 10px;
      background: var(--color-sub-background);
      border-radius: $border-radius;
      box-shadow: $box-shadow;

      &:before {
        content: "";
        position: absolute;
        top: -10px;
        left: 50%;
        width: 0;
        height: 0;
        border: {
          width: 5px;
          style: solid;
          color: transparent transparent var(--color-sub-background) transparent;
        }
      }
    }

    .sub-item {
      a {
        display: block;
        padding: 10px 15px;

        &:hover {
          background-color: $color-theme;
          color: #fff;
          transform: scaleX(1.05);
        }
      }
    }
  }
}
@media screen and (max-width: 1200px) {
  .header-container {
    .search-wrapper {
      display: none;

      &.is-show {
        position: fixed;
        top: 0;
        left: 0;
        z-index: $z-index + 999;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        background: #fff;

        .search-content {
          position: relative;
          width: 80%;
          height: 50px;
        }

        input[type="text"] {
          width: 100%;
          height: 100%;
          font-size: 14px;
        }

        .icon-search {
          width: 50px;
          height: 50px;
          font-size: 24px;
          line-height: 50px;
        }

        .icon-close {
          position: absolute;
          top: -60px;
          right: 0;
          display: block;
          background: none;
          font-size: 24px;
        }
      }
    }
  }
}
@media screen and (max-width:1024px) {
  .header-container {
    .nav-wrapper {
      position: fixed;
      top: 60px;
      right: 0;
      z-index: $z-index + 200;
      width: 200px;
      height: calc(100% - 60px);
      margin: 0;
      padding: 0 20px;
      overflow-y: scroll;
      -webkit-overflow-scrolling: touch;
      background: var(--color-menu-background-m);
      transition: .5s;
      transform: translateX(100%);

      &.is-show {
        transform: translateX(0);
      }

      .nav-view {
        flex-direction: column;
        justify-content: flex-start;
      }

      .nav-item {
        height: auto;

        .first-link {
          padding: 0;
          line-height: 40px;
        }
      }

      .sub-item {
        a {
          display: block;
          padding: 10px 10px 10px 25px;
        }
      }
    }

    .controller {
      display: flex;

      .icon-search,
      .icon-menu {
        font-size: 20px;
      }

      .icon-menu {
        display: block;
        margin-left: 10px;
      }
    }
  }
}
</style>
