<template>
  <div class="container">
    <article class="article">
      <h2 class="title">{{ pages.title.rendered }}</h2>
      <div class="content" v-html="pages.content.rendered"></div>
      <!-- 评论列表 -->
      <div class="section comment">
        <h2 class="comment-title" v-html="`共 ${pages.pageInfor.commentCount} 条评论关于 “${pages.title.rendered}”`"></h2>
        <no-ssr>
          <comments></comments>
        </no-ssr>
      </div>
    </article>
  </div>
</template>

<script>
import axios from '~/plugins/axios'
import Comments from '~/components/comment/Index'
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
  name: 'Page',
  components: {
    Comments
  },
  head () {
    return {
      title: this.pages.title.rendered,
      link: [
        { rel: 'stylesheet', href: 'https://upyun.xuanmo.xin/css/prism.css' }
      ],
      script: [
        { src: 'https://upyun.xuanmo.xin/js/prism.js' }
      ]
    }
  },
  created () {
    this.$store.commit('getInfo', {
      info: this.info,
      menu: this.menu
    })
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

.comment-title{
  margin-bottom: 10px;
  padding: 10px 0;
  border-radius: $border-radius;
  background: $color-sub-background;
  font-size: $font-size-large;
  text-align: center;
}
</style>
