<template>
  <div id="app">
    <app-header/>
    <div class="main">
      <div class="wrap">
        <div class="content">
          <nuxt/>
        </div>
        <!-- sidebar start -->
        <app-sidebar class="sidebar-wrap"/>
        <!-- sidebar end -->
      </div>
    </div>
    <app-footer/>
  </div>
</template>

<script>
import AppHeader from '~/components/AppHeader'
import AppFooter from '~/components/AppFooter'
import AppSidebar from '~/components/AppSidebar'
export default {
  name: 'App',
  components: {
    AppHeader,
    AppFooter,
    AppSidebar
  },
}
</script>

<style lang="scss">
@import "~assets/scss/global.scss";
</style>
<style lang="scss" scoped>
.wrap{
  display: flex;
  justify-content: space-between;
  margin-top: $container-margin;

  // 左边内容容器
  .content{
    width: 900px;
  }

  // 侧边栏
  .sidebar-wrap{
    width: 280px;
  }
}

@media screen and (max-width: 1024px) {
  .wrap{
    .content{
      width: 73%;
    }

    .sidebar-wrap{
      width: 25%;
    }
  }
}

@media screen and (max-width:768px) {
  .wrap{
    .content{
      width: 100%;
    }

    .sidebar-wrap{
      display: none;
    }
  }
}
</style>
