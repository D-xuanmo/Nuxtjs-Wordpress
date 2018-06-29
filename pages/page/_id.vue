<template>
  <div class="container">
    <article class="article">
      <h2 class="title">
        <span>{{ pages.title.rendered }}</span>
      </h2>
      <div class="content" v-html="pages.content.rendered"></div>
    </article>
    <!-- 评论列表 -->
    <div class="comment">
      <h2 class="comment-title" v-html="`共 ${pages.pageInfor.commentCount} 条评论关于 “${pages.title.rendered}”`"></h2>
      <no-ssr>
        <comments></comments>
      </no-ssr>
    </div>
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
  layout: 'page',
  components: {
    Comments
  },
  head () {
    return {
      title: `${this.pages.title.rendered} | ${this.info.blogName}`,
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
  border-radius: $border-radius;

  .title{
    margin-bottom: 30px;
    font-size: 20px;
    font-weight: bold;
    text-align: center;

    span{
      display: inline-block;
      color: #333;

      &:after{
        content: "";
        display: block;
        width: 20px;
        height: 2px;
        margin: 5px auto 0;
        background: $color-main-text;
      }
    }
  }

  // 正文
  .content{
    line-height: 2;

    /deep/ a{
      color: $color-highlight-text;
    }

    /deep/ h2{
      margin-top: 10px;
      font-weight: bold;

      .iconfont{
        vertical-align: bottom;
      }
    }

    /deep/ h1,
    /deep/ h2{
      font-size: $font-size-large;
      font-weight: bold;
    }

    /deep/ h3{
      font-weight: bold;
    }
    /deep/ h4,
    /deep/ h5,
    /deep/ h6{
      font-size: $font-size-small;
      font-weight: bold;
    }

    /deep/ img{
      height: auto !important;
      margin-top: 20px;
      box-shadow: 0 0 10px #d2d2d2;
    }
  }
}

.comment{
  margin-top: $container-margin;
  padding: $container-padding;
  background: $color-white;
  border-radius: $border-radius;

  .comment-title{
    margin-bottom: 10px;
    padding: 10px 0;
    border-radius: $border-radius;
    background: $color-sub-background;
    font-size: $font-size-large;
    text-align: center;
  }
}
</style>
