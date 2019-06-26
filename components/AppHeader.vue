<template>
  <header :class="['header-container', menuStatus && 'is-show-menu']">
    <div class="hide-header"></div>
    <div class="header-content" ref="header">
      <div class="wrap h-f-100">
        <!-- logo 开始 -->
        <div class="logo">
          <h1>{{ info.blogName }}</h1>
          <nuxt-link :to="{ name: 'index' }" class="block">
            <img :src="info.logo" class="vertical-middle" width="130" height="40">
          </nuxt-link>
        </div>
        <!-- logo结束 -->
        <!-- 导航开始 -->
        <div :class="['nav-wrapper', 'h-f-100', menuStatus && 'is-show']">
          <ul class="nav-view h-f-100">
            <li class="nav-item first h-f-100">
              <nuxt-link :to="{ name: 'index' }" class="first-link"><x-icon type="icon-home2"></x-icon>首页</nuxt-link>
            </li>
            <li class="nav-item h-f-100" v-for="item in menu" :key="item.key">
              <nuxt-link
                v-if="item.type === 'category'"
                :to="{
                  name: `${item.type}-id`,
                  params: { id: 1 },
                  query: { type: item.ID, title: item.title }
                }"
                :class="['first-link', item.children.length !== 0 && 'prohibit-event']"
              >
                <x-icon :type="item.icon"></x-icon> {{ item.title }}
                <x-icon v-if="item.children.length !== 0" type="icon-arrow-bottom"></x-icon>
              </nuxt-link>
              <nuxt-link
                v-else-if="item.type === 'page'"
                :to="{ name: 'page-id', params: { id: item.ID } }"
                class="first-link">
                <x-icon :type="item.icon"></x-icon> {{ item.title }}
              </nuxt-link>
              <nuxt-link v-else-if="item.type === 'custom'" :to="{ name: 'tags' }" class="first-link">
                <x-icon :type="item.icon"></x-icon> {{ item.title }}
              </nuxt-link>
              <!-- 二级菜单 -->
              <div v-if="item.children.length" class="sub-nav-wrapper">
                <ul class="sub-nav-view">
                  <li v-for="child in item.children" :key="child.key" class="sub-item">
                    <nuxt-link
                      v-if="child.type === 'category'"
                      :to="{
                        name: 'category-id',
                        params: { id: 1 },
                        query: { type: child.ID, title: child.title }
                      }"
                    >
                      <x-icon :type="child.icon"></x-icon> {{ child.title }}
                    </nuxt-link>
                    <nuxt-link
                      v-else-if="child.type === 'page'"
                      :to="{
                        name: 'page-id',
                        params: { id: child.ID }
                      }"
                    >
                      <x-icon :type="child.icon"></x-icon> {{ child.title }}
                    </nuxt-link>
                    <nuxt-link v-else-if="child.type === 'custom'" :to="{ name: 'tags' }">
                      <x-icon :type="child.icon"></x-icon> {{ child.title }}
                    </nuxt-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
        <!-- 导航结束 -->
        <!-- 右侧搜索开始 -->
        <div class="controller">
          <div :class="['search-wrapper', isShowSearch && 'is-show']">
            <div class="search-content">
              <input
                v-model="searchText"
                type="text"
                placeholder="请输入关键字"
                @keyup.enter="_search"
              >
              <x-icon type="icon-search" @click.native="_search"></x-icon>
              <x-icon type="icon-close hide"  @click.native="isShowSearch = false"></x-icon>
            </div>
          </div>
          <x-icon type="icon-search tablet-show" @click.native="isShowSearch = true"></x-icon>
          <x-icon type="icon-menu phone-show" @click.native="_showNavWrap"></x-icon>
        </div>
        <!-- 右侧搜索结束 -->
      </div>
    </div>
  </header>
</template>
<script>
import { mapState } from 'vuex'
import $ from '@/utils/operationDOM'
export default {
  watchQuery: ['type'],
  name: 'AppHeader',
  data () {
    return {
      searchText: '',
      isShowSearch: false,
      mark: true
    }
  },
  computed: {
    ...mapState(['info', 'menu', 'menuStatus']),
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
  background: #fff;

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
    background: #fff;
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
    margin: 0 30px 0 20px;
    .nav-view {
      display: flex;
      justify-content: space-between;
      // &:after {
      //   content: "";
      //   margin: auto;
      // }
    }

    .nav-item {
      position: relative;
      .first-link {
        display: block;
        line-height: $headerHeight;
      }

      &:hover {
        .sub-nav-wrapper {
          display: block;
        }
      }
    }
  }

  // 搜索框
  .search-wrapper {
    width: 200px;

    .search-content {
      display: flex;
      align-items: center;
      border: 1px solid $color-main-background;
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
      cursor: pointer;
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
      background: #fff;
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
          color: transparent transparent #fff transparent;
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
@media screen and (max-width:768px) {
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
      background: $color-theme;
      transition: .5s;
      transform: translateX(100%);

      &.is-show {
        transform: translateX(0);
      }

      a {
        color: #fff;
      }

      .nav-view {
        flex-direction: column;
        justify-content: flex-start;
      }

      .nav-item {
        height: auto;

        .first-link {
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
        margin-left: 10px;
      }
    }
  }
}
</style>
