<template>
  <header class="header">
    <div class="wrap">
      <div class="left">
        <div class="logo"><a :href="$store.state.info.baseUrl">{{ $store.state.info.blogName }}</a></div>
        <nav class="nav-wrap">
          <ul class="box">
            <li class="nav-list">
              <nuxt-link to="/">首页</nuxt-link>
            </li>
            <li class="nav-list" v-for="item in navList" :key="item.key">
              <nuxt-link :to="{ name: `${item.type}-id`, params: { id: item.ID } }">
                {{ item.title }}
                <i class="iconfont icon-arrow-bottom" v-if="item.children.length !== 0"></i>
              </nuxt-link>
              <!-- 二级菜单 -->
              <ul class="sub-nav-wrap">
                <li class="sub-nav-list" v-for="child in item.children" :key="child.key">
                  <nuxt-link :to="{ name: `${child.type}-id`, params: { id: child.ID } }">{{ child.title }}</nuxt-link>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
      <div class="right">
        <div class="search-wrap">
          <input type="text" v-model="searchText" placeholder="请输入关键字">
          <i class="iconfont icon-search"></i>
        </div>
      </div>
    </div>
  </header>
</template>
<script>
import axios from '~/plugins/axios'
export default {
  name: 'AppHeader',
  data () {
    return {
      searchText: '',
      navList: []
    }
  },
  created () {
    axios.get('/wp-json/xm-blog/v1/menu').then(res => (this.navList = res.data.menu)).catch(err => console.log(err))
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
    width: 600px;

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

      .icon-arrow-bottom{
        font-size: $font-size-small;
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
      background: $color-white;
      transform: translateX(-50%);
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
    }
  }
}
</style>
