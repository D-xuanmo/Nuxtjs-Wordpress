<template>
  <div class="container">
    <ul class="header">
      <li class="list">共<span class="mark">{{ totalPage }}</span>条关于“<span class="mark">{{ $route.query.s }}</span>”的文章</li>
    </ul>
    <article class="article-list" v-for="item in articleList" :key="item.key">
      <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" class="thumbnail-wrap">
        <img :src="item.articleInfor.thumbnail" class="thumbnail" alt="">
      </nuxt-link>
      <div class="list-content">
        <h2 class="title">
          <span class="classify">{{ item.articleInfor.classify[0].name }}</span>
          <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" class="vertical-middle" v-html="item.title.rendered"></nuxt-link>
        </h2>
        <p class="summary">{{ item.articleInfor.summary }}</p>
        <div class="opeartion">
          <div class="information">
            <span><x-icon type="icon-date"></x-icon>{{ item.date }}</span>
            <span><x-icon type="icon-hot1"></x-icon>{{ item.articleInfor.viewCount }}</span>
            <span><x-icon type="icon-message"></x-icon>{{ item.articleInfor.commentCount }}</span>
            <span><x-icon type="icon-good"></x-icon>{{ item.articleInfor.xmLike.very_good }}</span>
          </div>
          <nuxt-link class="details-btn" :to="{ name: 'details-id', params: { id: item.id } }">阅读详情</nuxt-link>
        </div>
      </div>
    </article>
    <!-- more btn start -->
    <el-pagination
      small
      :page-size="8"
      layout="prev, pager, next, jumper"
      :current-page="currentPage"
      @current-change="_changePagination"
      :total="totalPage">
    </el-pagination>
    <!-- more btn end -->
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'Search',
  watchQuery: ['page', 's'],
  fetch ({ params, query, store }) {
    store.commit('article/SET_CURRENT_PAGE', 1)
    return store.dispatch('article/getArticleList', {
      search: query.s,
      page: query.page,
      per_page: 8,
      _embed: true
    })
  },
  computed: {
    ...mapState(['info']),
    ...mapState('article', ['articleList', 'totalPage', 'currentPage'])
  },
  head () {
    return {
      title: `关于“${this.$route.query.s}”的文章 | ${this.info.blogName}`
    }
  },
  methods: {
    _changePagination (id) {
      this.$store.commit('article/SET_CURRENT_PAGE', id)
      this.$router.push({
        name: 'search',
        query: {
          page: id,
          s: this.$route.query.s
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
// 文章列表
.container {
  padding: $container-padding;
  background: $color-white;
  border-radius: $border-radius;

  .header {
    padding-bottom: $container-padding;
    border-bottom: 1px solid $color-main-background;
    font-size: $font-size-large;

    .mark {
      color: $color-theme;
    }
  }
}

@media screen and (max-width:767px) {
  // 文章列表
  .container {
    .article-list {
      flex-wrap: wrap;
      height: auto;

      .title {
        margin-top: 15px;
        font-size: $font-size-large;
      }

      .summary {
        height: auto;
      }

      .list-content {
        height: auto;
      }

      .opeartion {
        position: static;
        display: block;
        margin-top: 10px;
      }

      .details-btn {
        display: block;
        margin-top: 10px;
        padding: 10px 0;
        text-align: center;
      }
    }

    .thumbnail-wrap {
      width: 100%;
      margin-right: 0;
      text-align: center;

      .thumbnail {
        width: auto;
        height: auto;
        max-height: 150px;
      }
    }
  }

  // 翻页
  /deep/ .el-pagination {
    .el-pagination__jump {
      display: none;
    }
  }
}
</style>
