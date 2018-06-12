<template>
  <section class="container">
    <article class="article">
      <h2 class="title">{{ article.title.rendered }}</h2>
      <div class="other-info">
        <span class="author">{{ article.articleInfor.author }}</span>
        <span class="text">&nbsp;发表于：</span>
        <span class="time"><i class="iconfont icon-time"></i> {{ article.date.replace('T', ' ') }}</span>
        <span class="text">&nbsp;分类：</span>
        <span class="classify" v-for="item in article.articleInfor.classify" :key="item.key">{{ item.name }}</span>&nbsp;
        <i class="iconfont icon-hot1">{{ article.articleInfor.viewCount }}</i>&nbsp;
        <i class="iconfont icon-message-f">{{ article.articleInfor.commentCount }}</i>
      </div>
      <div class="content" v-html="article.content.rendered"></div>
    </article>
  </section>
</template>
<script>
import axios from '~/plugins/axios'
export default {
  async asyncData (context) {
    let [info, menu, article] = await Promise.all([
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/info`),
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/menu`),
      axios.get(`${process.env.baseUrl}/wp-json/wp/v2/posts/${context.route.params.id}`)
    ])
    return {
      info: info.data,
      menu: menu.data.mainMenu,
      article: article.data
    }
  },
  name: 'Article',
  data () {
    return {
    }
  },
  created () {
    console.log(this)
    this.$store.commit('getInfo', {
      info: this.info,
      menu: this.menu
    })
  },
  head () {
    return {
      title: this.article.title.rendered,
      link: [
        { rel: 'stylesheet', href: 'https://www.xuanmo.xin/wp-content/themes/xm-vue-theme/static/css/prism.css' }
      ],
      script: [
        { src: 'https://www.xuanmo.xin/wp-content/themes/xm-vue-theme/static/js/prism.js' }
      ]
    }
  }
}
</script>
<style lang="scss" scoped>
.article{
  padding: $container-padding;
  background: $color-white;

  .title{
    padding: 10px 0;
    font-size: 20px;
    text-align: center;
  }

  .other-info{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-main-background;
    text-align: center;
  }

  // 正文
  .content{
    line-height: 2;
  }
}
</style>
