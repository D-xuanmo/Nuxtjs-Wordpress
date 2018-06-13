<template>
  <div class="container">
    <article class="article">
      <h2 class="title">{{ pages.title.rendered }}</h2>
      <div class="content" v-html="pages.content.rendered"></div>
    </article>
  </div>
</template>

<script>
import axios from '~/plugins/axios'
export default {
  async asyncData ({ params }) {
    let [info, menu, pages] = await Promise.all([
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/info`),
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/menu`),
      axios.get(`${process.env.baseUrl}/wp-json/wp/v2/pages/${params.id}`)
    ])
    return {
      info: info.data,
      menu: menu.data.mainMenu,
      pages: pages.data
    }
  },
  name: 'Index',
  data () {
    return {
      isShowLoading: true
    }
  },
  head () {
    return {
      title: this.pages.title.rendered,
      link: [
        { rel: 'stylesheet', href: 'https://www.xuanmo.xin/wp-content/themes/xm-vue-theme/static/css/prism.css' }
      ],
      script: [
        { src: 'https://www.xuanmo.xin/wp-content/themes/xm-vue-theme/static/js/prism.js' }
      ]
    }
  },
  created () {
    this.$store.commit('getInfo', {
      info: this.info,
      menu: this.menu
    })
    this.isShowLoading = false
    console.log(this)
  }
}
</script>

<style lang="scss" scoped>
.article{
  padding: $container-padding;
  background: $color-white;

  .title{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-main-background;
    font-size: 20px;
    text-align: center;
  }

  // 正文
  .content{
    line-height: 2;
  }
}
</style>
