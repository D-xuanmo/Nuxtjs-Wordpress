<template>
  <div class="comments-wrap">
    <!-- 发表评论 -->
    <template>
      <div v-if="commentStatus === 'closed'" class="comment-closed align-center f-s-large">评论已关闭</div>
      <comment-form v-else-if="commentStatus === 'open'" :post-id="pageId || $route.params.id" />
    </template>

    <p v-if="commentListLoading" class="align-center">
      <img src="../../assets/images/circle-loading-icon.svg" alt="">
    </p>

    <comment-list :list="commentList" :page-id="pageId || $route.params.id" />

    <div class="align-center">
      <button v-if="!commentListLoading" class="comment-list-more-btn" @click="handleLoadingMore">
        <x-icon v-if="loadingMore.loading" type="icon-loading" class="vertical-middle" />
        <span class="vertical-middle">{{ isLastPage ? '我是有底线的~' : loadingMore.text }}</span>
        <x-icon v-if="!loadingMore.loading && !isLastPage" type="icon-arrow-bottom" class="vertical-middle" />
      </button>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import CommentForm from './CommentForm'
import CommentList from './CommentList'
import XIcon from '../Icon/main'

export default {
  name: 'Comments',

  components: {
    XIcon,
    CommentList,
    CommentForm
  },

  props: {
    pageId: [String, Number],
    commentStatus: {
      type: String,
      default: 'open'
    }
  },

  data() {
    return {
      currentPage: 1,
      pageSize: 10,

      commentListLoading: true,

      loadingMore: {
        loading: false,
        text: '加载更多'
      }
    }
  },

  computed: {
    ...mapState(['globalConfig']),
    ...mapState({
      isOpenCommentUpload: state => state.globalConfig.isOpenCommentUpload,
      templateUrl: state => state.globalConfig.templeteUrl
    }),
    ...mapState('comment', ['commentList', 'totalPage']),

    isLastPage() {
      return this.totalPage === 0 || this.currentPage === this.totalPage
    }
  },

  created() {
    this.getList()
  },

  beforeDestroy() {
    this.$store.commit('comment/RESET_COMMENT')
  },

  methods: {
    ...mapActions('comment', ['getCommentList', 'updateComment', 'getExpression', 'updateCommentOpinion']),

    async getList() {
      await this.getCommentList({
        postId: this.pageId || this.$route.params.id,
        page: this.currentPage,
        pageSize: this.pageSize
      })
      this.commentListLoading = false
    },

    async handleLoadingMore() {
      if (this.isLastPage) return
      this.loadingMore.text = '加载中'
      this.loadingMore.loading = true
      try {
        await this.getCommentList({
          postId: this.pageId || this.$route.params.id,
          page: this.currentPage + 1,
          pageSize: this.pageSize
        })
        this.loadingMore.text = '加载更多'
        this.loadingMore.loading = false
        this.currentPage++
      } catch (err) {
        this.loadingMore.text = '加载更多'
        this.loadingMore.loading = false
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.comment-list-more-btn {
  margin-top: var(--large-gap);
  color: var(--color-secondary);

  .iconfont {
    font-size: 16px;
    color: var(--color-secondary);
  }
}

::v-deep .comment-list-item--upload-img {
  max-width: 40%;
  vertical-align: bottom;
}

@media screen and (max-width: 768px) {
  ::v-deep .comment-list-item--upload-img {
    max-width: 100%;
  }
}
</style>
