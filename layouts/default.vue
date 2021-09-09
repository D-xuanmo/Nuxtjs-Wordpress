<template>
  <div id="app">
    <div :class="['menu-mask', menuStatus && 'is-show-menu']" @click="_closeMenu"></div>
    <app-header/>
    <div :class="['main', menuStatus && 'is-show-menu']">
      <div id="content" :class="['wrap', isReadingMode && 'is-reading-mode']">
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
    ...mapState(['menuStatus', 'isReadingMode'])
  },

  watch: {
    isReadingMode(status) {
      setTimeout(() => {
        this.isHideSidebar = status
      }, 300)
    }
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

#content {
  &.is-reading-mode {
    .content {
      width: 100%;
    }

    .sidebar-wrap {
      width: 0;
      visibility: hidden;
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
