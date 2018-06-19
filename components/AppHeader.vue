<template>
  <header class="header">
    <div class="wrap">
      <div class="left">
        <div class="logo">
          <nuxt-link :to="{ name: 'index' }">{{ $store.state.info.blogName }}</nuxt-link>
        </div>
        <nav class="nav-wrap">
          <no-ssr>
            <ul class="box">
              <li class="nav-list first">
                <nuxt-link :to="{ name: 'index' }"><i class="iconfont icon-home2"></i>首页</nuxt-link>
              </li>
              <li class="nav-list" v-for="item in $store.state.menu" :key="item.key">
                <nuxt-link :to="{ name: `${item.type}-id`, params: { id: 1 }, query: { type: item.ID, title: item.title } }">
                  <i class="iconfont" :class="item.icon"></i>{{ item.title }}
                  <i class="iconfont icon-arrow-bottom" v-if="item.children.length !== 0"></i>
                </nuxt-link>
                <!-- 二级菜单 -->
                <div class="sub-nav-wrap">
                  <ul class="list-view-wrap">
                    <li class="sub-nav-list" v-for="child in item.children" :key="child.key">
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
          <input type="text" v-model="searchText" placeholder="请输入关键字" @keyup.enter="search">
          <i class="iconfont icon-search" @click="search"></i>
        </div>
      </div>
    </div>
  </header>
</template>
<script>
export default {
  watchQuery: ['type'],
  key: (to) => to.fullPath,
  name: 'AppHeader',
  data () {
    return {
      searchText: ''
    }
  },
  methods: {
    search () {
      this.$router.push({
        name: 'search',
        query: {
          page: 1,
          s: this.searchText
        }
      })
      this.searchText = ''
    }
  }
}
</script>
<style lang="scss" scoped>
$headerHeight: 60px;
.header{
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
  }

  // 搜索框
  .search-wrap{
    display: flex;
    align-items: center;
    width: 200px;
    border: 1px solid $color-main-background;
    border-radius: $border-radius;

    input[type="text"]{
      width: 160px;
      height: 30px;
      padding: 0 5px;
    }

    .icon-search{
      width: 30px;
      height: 30px;
      background: $color-main-background;
      text-align: center;
      line-height: 30px;
      cursor: pointer;
    }
  }
}
</style>
