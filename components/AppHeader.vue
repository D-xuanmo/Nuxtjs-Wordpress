<template>
  <header class="header">
    <div v-if="isFloat" class="hide-header"></div>
    <div class="header-content" :class="{ 'is-float': isFloat }" ref="header">
      <div class="wrap">
        <div class="left">
          <div class="logo">
            <h1>
              <span class="hide">{{ $store.state.info.blogName }}</span>
              <no-ssr>
                <nuxt-link :to="{ name: 'index' }" class="block">
                  <img :src="$store.state.info.logo" class="vertical-middle" width="130" height="40" alt="">
                </nuxt-link>
              </no-ssr>
            </h1>
          </div>
          <nav class="nav-wrap" :class="{ show: isShowNavWrap }">
            <no-ssr>
              <ul class="box">
                <li class="nav-list first">
                  <nuxt-link :to="{ name: 'index' }"><x-icon type="icon-home2" @click.native="hideNavWrap"></x-icon>首页</nuxt-link>
                </li>
                <li class="nav-list" v-for="item in $store.state.menu" :key="item.key">
                  <nuxt-link
                     v-if="item.type === 'category'"
                    :to="{ name: `${item.type}-id`, params: { id: 1 }, query: { type: item.ID, title: item.title } }"
                    :class="{ 'prohibit-event': item.children.length !== 0 }"
                    @click.native="hideNavWrap">
                    <x-icon :type="item.icon"></x-icon>{{ item.title }}
                    <x-icon type="icon-arrow-bottom" v-if="item.children.length !== 0"></x-icon>
                  </nuxt-link>
                  <nuxt-link v-else-if="item.type === 'page'" :to="{ name: 'page-id', params: { id: item.ID } }">
                    <x-icon :type="item.icon"></x-icon> {{ item.title }}
                  </nuxt-link>
                  <nuxt-link v-else-if="item.type === 'custom'" :to="{ name: 'tags' }">
                    <x-icon :type="item.icon"></x-icon> {{ item.title }}
                  </nuxt-link>
                  <!-- 二级菜单 -->
                  <div class="sub-nav-wrap" :class="{ not: item.children.length === 0 }">
                    <ul class="list-view-wrap">
                      <li class="sub-nav-list" v-for="child in item.children" :key="child.key" @click="hideNavWrap">
                        <nuxt-link v-if="child.type === 'category'" :to="{ name: 'category-id', params: { id: 1 }, query: { type: child.ID, title: child.title } }">
                          <x-icon :type="child.icon"></x-icon> {{ child.title }}
                        </nuxt-link>
                        <nuxt-link v-else-if="child.type === 'page'" :to="{ name: 'page-id', params: { id: child.ID } }">
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
            </no-ssr>
          </nav>
        </div>
        <div class="right">
          <div class="search-wrap">
            <input type="text" class="desktop-show" :class="{ show: isShowSearch }" v-model="searchText" placeholder="请输入关键字" @keyup.enter="search">
            <x-icon type="icon-search" @click.native="showSearch"></x-icon>
            <x-icon type="icon-menu phone-show" @click.native="showNavWrap"></x-icon>
            <x-icon type="icon-close phone-show" :class="{ show: isShowNavWrap }" @click.native="hideNavWrap"></x-icon>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
<script>
export default {
  watchQuery: ['type'],
  name: 'AppHeader',
  data () {
    return {
      searchText: '',
      isShowNavWrap: false,
      isShowSearch: false,
      isFloat: false
    }
  },
  mounted () {
    let self = this
    window.addEventListener('scroll', function () {
      self.isFloat = this.scrollY > 500 ? true : false
    }, false)
  },
  methods: {
    // 搜索
    search () {
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

    // 显示搜索框
    showSearch () {
      if (window.innerWidth > 1024) {
        this.search()
      } else {
        this.isShowSearch = !this.isShowSearch
      }
    },

    // 显示菜单
    showNavWrap () {
      this.isShowNavWrap = true
    },

    // 隐藏菜单
    hideNavWrap () {
      this.isShowNavWrap = false
    }
  }
}
</script>
<style lang="scss" scoped>
$headerHeight: 60px;
.header{
  position: relative;
  width: 100%;
  height: 60px;
}

.hide-header {
  height: 60px;
}

.header-content {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 999;
  width: 100%;
  height: 100%;
  background: $color-white;

  &.is-float {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 999;
    height: 60px;
    box-shadow: 0 0 15px rgba(0,0,0,.2);
  }
}

.wrap{
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: $headerHeight;

  .left,
  .right{
    display: flex;
    align-items: center;
  }

  .logo{
    max-width: 150px;
    margin-right: 20px;

    h1{
      display: flex;
      align-items: center;
      height: 60px;
    }
  }

  // 导航
  .nav-wrap{
    width: 700px;

    .box{
      display: flex;
    }
  }

  // 一级菜单
  .nav-list{
    display: flex;
    align-items: center;
    position: relative;
    height: $headerHeight;
    margin-right: 30px;

    &:last-of-type{
      margin-right: 0;
    }

    &:hover{
      .sub-nav-wrap{
        display: block;
      }
    }

    .iconfont{
      vertical-align: bottom;
      margin-right: 2px;
    }

    .icon-arrow-bottom{
      font-size: $font-size-small;
    }

    .nuxt-link-exact-active{
      color: $color-theme;
    }
  }

  // 二级菜单
  .sub-nav-wrap{
    display: none;
    position: absolute;
    z-index: 99;
    top: 60px;
    left: 50%;
    width: 150px;
    transform: translateX(-50%);

    &.not{
      display: none !important;
    }

    &:after{
      content: "";
      position: absolute;
      top: 1px;
      left: 50%;
      border: {
        width: 5px;
        style: solid;
        color: transparent transparent $color-white transparent;
      }
      transform: translateX(-50%);
    }

    .list-view-wrap{
      overflow: hidden;
      margin-top: 10px;
      border: 1px solid $color-sub-background;
      box-shadow: 0 2px 20px 0 rgba(0,0,0,.3);
      border-radius: $border-radius;
      background: $color-white;
    }
  }

  .sub-nav-list{
    a{
      display: block;
      padding: 10px;
      transition: 0s;
    }

    &:hover{
      background: $color-theme;

      a{
        color: $color-white;
      }
    }
  }

  // 搜索框
  .search-wrap{
    display: flex;
    align-items: center;
    width: 200px;
    border: 1px solid $color-main-background;
    border-radius: $border-radius;

    input[type="text"]{
      -webkit-appearance: none;
      width: 160px;
      height: 30px;
      padding: 0 5px;
    }

    .iconfont{
      width: 30px;
      height: 30px;
      background: $color-main-background;
      text-align: center;
      line-height: 30px;
      cursor: pointer;
    }
  }
}

@media screen and (max-width: 1024px) {
  .wrap{
    .search-wrap{
      width: initial;
      border: 0;

      input[type="text"]{
        display: block;
        box-sizing: border-box;
        position: fixed;
        z-index: 99;
        top: 60px;
        right: 0;
        width: 100%;
        height: 50px;
        padding: 0 5%;
        border-top: 1px solid $color-main-background;
        border-radius: 0;
        box-shadow: 0 2px 5px $color-main-background;
        background: $color-white;
        transition: .7s;
        transform: translateX(100%);

        &.show{
          transform: translateX(0);
        }
      }

      .iconfont{
        width: initial;
        background: none;
      }
    }
  }
}

@media screen and (max-width:768px) {
  .wrap{
    .search-wrap{
      .iconfont{
        font-size: 20px;
      }

      .icon-menu{
        margin-left: 20px;
      }

      // 关闭按钮
      .icon-close{
        position: absolute;
        z-index: -1;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        opacity: 0;
        background: $color-theme;
        font-size: 20px;
        line-height: 60px;
        text-align: center;
        color: $color-white;
        transition: .5s;

        &.show{
          z-index: 99;
          right: 60%;
          opacity: 1;
          transform: translateX(-15px);
        }
      }
    }

    // 导航
    .nav-wrap{
      overflow-y: scroll;
      -webkit-overflow-scrolling: touch;
      position: fixed;
      z-index: 999;
      top: 0;
      right: 0;
      width: 60%;
      height: 100%;
      padding-left: $container-padding;
      opacity: 0;
      background: $color-theme;
      color: $color-white;
      transition: .5s;
      transform: translateX(100%);

      &.show{
        opacity: 1;
        transform: translateX(0px);
      }

      .box{
        display: block;
        height: 100%;
      }

      a{
        color: $color-white !important;
      }
    }

    .nav-list{
      display: block;
      width: 100%;
      height: auto;
      margin: 0;

      > a{
        display: block;
        width: 100%;
        height: 50px;
        line-height: 50px;
      }
    }

    // 二级导航
    .sub-nav-wrap{
      display: block !important;
      box-sizing: border-box;
      position: static;
      width: 100%;
      padding-left: 10px;
      transform: translateX(0);

      &:after{
        display: none;
      }

      .list-view-wrap{
        background: none;
        border: 0;
        box-shadow: none;
      }
    }
  }
}
</style>
