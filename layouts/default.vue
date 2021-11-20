<template>
  <div id="app">
    <div :class="['menu-mask', menuStatus && 'is-show-menu']" @click="_closeMenu"></div>
    <app-header/>
    <div :class="['main', menuStatus && 'is-show-menu']">
      <div id="content" :class="['wrap']">
        <div class="content">
          <nuxt />
        </div>
        <!-- sidebar start -->
        <app-sidebar :class="['sidebar-wrap', isHideSidebar && 'is-hide']" />
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
import { mapState } from 'vuex'

export default {
  name: 'App',

  components: {
    AppHeader,
    AppFooter,
    AppSidebar
  },

  data() {
    return {
      isHideSidebar: false
    }
  },

  computed: {
    ...mapState(['menuStatus'])
  },

  methods: {
    _closeMenu() {
      this.$store.commit('UPDATE_MENU_STATUS', false)
    }
  }
}
</script>

<style lang="scss" scoped>
.wrap {
  display: flex;
  justify-content: space-between;
  margin-top: $container-margin;

  // 左边内容容器
  .content {
    width: calc(100% - 280px - 15px);
    transition: .5s;
  }

  // 侧边栏
  .sidebar-wrap {
    width: 280px;
    transition: .3s;

    &.is-hide {
      display: none;
    }
  }
}

@media screen and (max-width: 1024px) {
  .wrap {
    .content {
      width: 100%;
    }

    .sidebar-wrap {
      display: none;
    }
  }
}
</style>
