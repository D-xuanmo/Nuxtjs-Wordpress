<template>
  <div class="container">
    <article class="article">
      <h2 class="title">
        <span v-html="detail.title.rendered"></span>
      </h2>
      <div class="content-details" v-html="detail.content.rendered"></div>
    </article>
    <!-- 评论列表 -->
    <div class="comment">
      <h2 class="comment-title" v-html="`共 ${detail.pageInfor.commentCount} 条评论关于 “${detail.title.rendered}”`"></h2>
      <client-only>
        <comments :comment-status="detail.comment_status"/>
      </client-only>
    </div>
  </div>
</template>

<script>
import Comments from '~/components/Comment'
import { mapState } from 'vuex'
export default {
  name: 'Page',
  layout: 'page',
  fetch ({ params, store }) {
    return store.dispatch('page/getPageDetail', params.id)
  },
  components: {
    Comments
  },
  computed: {
    ...mapState(['info']),
    ...mapState('page', ['detail'])
  },
  head () {
    return {
      title: `${this.detail.title.rendered} | ${this.info.blogName}`,
      link: [
        { rel: 'stylesheet', href: 'https://upyun.xuanmo.xin/css/prism.css' }
      ],
      style: [
        { cssText: this.info.detailsCss, type: 'text/css' }
      ],
      script: [
        { src: 'https://upyun.xuanmo.xin/js/prism.js' }
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
.article {
  padding: $container-padding;
  background: $color-white;
  border-radius: $border-radius;

  .title {
    margin-bottom: 30px;
    font-size: 20px;
    text-align: center;

    span {
      display: inline-block;
      color: #333;

      &:after {
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
  .content-details {
    line-height: 2;

    /deep/ a {
      color: $color-theme;
    }

    /deep/ h2{
      margin-top: 10px;

      .iconfont {
        vertical-align: bottom;
      }
    }

    /deep/ h1,
    /deep/ h2{
      font-size: $font-size-large;
    }

    /deep/ h4,
    /deep/ h5,
    /deep/ h6{
      font-size: $font-size-small;
    }

    /deep/ img {
      height: auto !important;
      box-shadow: 0 0 10px #d2d2d2;
    }
  }
}

.comment {
  margin-top: $container-margin;
  padding: $container-padding;
  background: $color-white;
  border-radius: $border-radius;

  .comment-title {
    margin-bottom: 10px;
    padding: 10px 0;
    border-radius: $border-radius;
    background: $color-sub-background;
    font-size: $font-size-large;
    text-align: center;
  }
}
</style>
