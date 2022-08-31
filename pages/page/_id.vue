<template>
  <page-detail
    :title="detail.title.rendered"
    :content="detail.content.rendered"
    :comment-count="detail.pageInfor.commentCount"
    :comment-status="detail.comment_status"
  />
</template>

<script>
import PageDetail from '~/components/PageDetail'
import { mapState } from 'vuex'
export default {
  name: 'Page',

  layout: 'page',

  fetch ({ params, store }) {
    return store.dispatch('page/getPageDetail', params.id)
  },

  components: {
    PageDetail
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
