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
              <nuxt-link :to="{ name: `${item.type}-id`, params: { id: item.ID } }">{{ item.title }}</nuxt-link>
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
.header{
  background: $color-white;
}

.wrap{
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 60px;

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

    .nav-list{
      margin-right: 30px;

      &:last-of-type{
        margin-right: 0;
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
