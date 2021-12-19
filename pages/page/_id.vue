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
      style: [
        { cssText: this.info.detailsCss, type: 'text/css' }
      ]
    }
  },

  mounted() {
    // eslint-disable-next-line
    document.querySelectorAll('pre code').forEach(el => Prism.highlightElement(el))
  }
}
</script>

<style lang="scss" scoped>
.article {
  padding: $container-padding;
  background: var(--color-sub-background);
  border-radius: $border-radius;

  .title {
    margin-bottom: 30px;
    font-size: 20px;
    text-align: center;

    span {
      display: inline-block;
      color: var(--color-primary);

      &:after {
        content: "";
        display: block;
        width: 20px;
        height: 2px;
        margin: 5px auto 0;
        background: var(--color-primary);
      }
    }
  }
}

.comment {
  margin-top: $container-margin;
  padding: $container-padding;
  background: var(--color-sub-background);
  border-radius: $border-radius;

  .comment-title {
    margin-bottom: 10px;
    padding: 10px 0;
    border-radius: $border-radius;
    background: var(--color-main-background);
    font-size: $font-size-large;
    text-align: center;
  }
}
</style>
