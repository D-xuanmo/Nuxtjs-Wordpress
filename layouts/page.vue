<template>
  <div id="app">
    <div :class="['menu-mask', menuStatus && 'is-show-menu']" @click="_closeMenu"></div>
    <app-header />
    <div :class="['main', menuStatus && 'is-show-menu']">
      <div class="wrap">
        <div class="content">
          <nuxt />
        </div>
      </div>
    </div>
    <app-footer />
  </div>
</template>

<script>
import AppHeader from '~/components/AppHeader'
import AppFooter from '~/components/AppFooter'
import { mapState } from 'vuex'
import hljs from 'highlight.js/lib/core'
import javascript from 'highlight.js/lib/languages/javascript'
import typescript from 'highlight.js/lib/languages/typescript'
import html from 'highlight.js/lib/languages/vbscript-html'
import css from 'highlight.js/lib/languages/css'
import json from 'highlight.js/lib/languages/json'
import yaml from 'highlight.js/lib/languages/yaml'
import less from 'highlight.js/lib/languages/less'
import plaintext from 'highlight.js/lib/languages/plaintext'
import php from 'highlight.js/lib/languages/php'
import scss from 'highlight.js/lib/languages/scss'
import markdown from 'highlight.js/lib/languages/markdown'
import sql from 'highlight.js/lib/languages/sql'
import bash from 'highlight.js/lib/languages/bash'
import 'highlight.js/styles/atom-one-dark-reasonable.css'

hljs.registerLanguage('javascript', javascript)
hljs.registerLanguage('typescript', typescript)
hljs.registerLanguage('html', html)
hljs.registerLanguage('css', css)
hljs.registerLanguage('json', json)
hljs.registerLanguage('yaml', yaml)
hljs.registerLanguage('less', less)
hljs.registerLanguage('text', plaintext)
hljs.registerLanguage('php', php)
hljs.registerLanguage('scss', scss)
hljs.registerLanguage('markdown', markdown)
hljs.registerLanguage('sql', sql)
hljs.registerLanguage('bash', bash)

export default {
  name: 'Page',
  components: {
    AppHeader,
    AppFooter
  },
  computed: {
    ...mapState(['menuStatus'])
  },
  beforeDestroy() {
    document.querySelectorAll('.prism-previewer').forEach(item => (item.style.display = 'none'))
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

  .content {
    width: 100%;
  }
}
</style>
