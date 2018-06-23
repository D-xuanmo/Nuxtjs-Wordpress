<template>
  <header class="header">
    <div class="wrap">
      <div class="left">
        <div class="logo">
          <nuxt-link :to="{ name: 'index' }">{{ $store.state.info.blogName }}</nuxt-link>
        </div>
        <nav class="nav-wrap" :class="{ show: isShowNavWrap }">
          <no-ssr>
            <ul class="box">
              <li class="nav-list first">
                <nuxt-link :to="{ name: 'index' }"><i class="iconfont icon-home2" @click.native="hideNavWrap"></i>首页</nuxt-link>
              </li>
              <li class="nav-list" v-for="item in $store.state.menu" :key="item.key">
                <nuxt-link :to="{ name: `${item.type}-id`, params: { id: 1 }, query: { type: item.ID, title: item.title } }" :class="{ 'prohibit-event': item.children.length !== 0 }" @click.native="hideNavWrap">
                  <i class="iconfont" :class="item.icon"></i>{{ item.title }}
                  <i class="iconfont icon-arrow-bottom" v-if="item.children.length !== 0"></i>
                </nuxt-link>
                <!-- 二级菜单 -->
                <div class="sub-nav-wrap" :class="{ not: item.children.length === 0 }">
                  <ul class="list-view-wrap">
                    <li class="sub-nav-list" v-for="child in item.children" :key="child.key" @click="hideNavWrap">
                      <nuxt-link v-if="child.type === 'category'" :to="{ name: 'category-id', params: { id: 1 }, query: { type: child.ID, title: child.title } }">
                        <i class="iconfont" :class="child.icon"></i> {{ child.title }}
                      </nuxt-link>
                      <nuxt-link v-else-if="child.type === 'page'" :to="{ name: 'page-id', params: { id: child.ID } }">
                        <i class="iconfont" :class="child.icon"></i> {{ child.title }}
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
          <i class="iconfont icon-search" @click="showSearch"></i>
          <i class="iconfont icon-menu phone-show" @click="showNavWrap"></i>
          <i class="iconfont icon-close phone-show" :class="{ show: isShowNavWrap }" @click="hideNavWrap"></i>
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
      isShowSearch: false
    }
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
  background: $color-white;
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
    font-size: 22px;
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
      color: $color-highlight-text;
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
      margin-top: 10px;
      border: 1px solid $color-sub-background;
      box-shadow: 0 2px 12px 0 rgba(0,0,0,.1);
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
      background: $color-highlight-text;

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
        background: $color-highlight-text;
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
      background: $color-highlight-text;
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
