<template>
  <div class="container">
    <ul class="header">
      <li class="list">所有文章</li>
    </ul>
    <article class="article-list" v-for="item in articleList" :key="item.key">
      <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" class="thumbnail-wrap">
        <img :src="item.articleInfor.thumbnail === null ? $store.state.info.extra.thumbnail : item.articleInfor.thumbnail.replace(/https?:\/\/.+\:\d+/, '')" class="thumbnail" alt="">
      </nuxt-link>
      <div class="list-content">
        <h2 class="title">
          <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" v-html="item.title.rendered"></nuxt-link>
        </h2>
        <p class="summary">{{ item.articleInfor.summary }}</p>
        <div class="opeartion">
          <div class="information">
            <span><x-icon type="icon-date"></x-icon>{{ item.date.replace('T', ' ') }}</span>
            <span><x-icon type="icon-eye"></x-icon>{{ item.articleInfor.viewCount }}</span>
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
      :current-page.sync="nCurrentPage"
      @current-change="currentPage"
      @prev-click="prevPage"
      @next-click="nextPage"
      :total="total">
    </el-pagination>
    <!-- more btn end -->
  </div>
</template>

<script>
import API from '~/api'
export default {
  async asyncData ({ params, error, store }) {
    try {
      let [list] = await Promise.all([
        API.getArticleList({
          page: params.id,
          per_page: 8,
          _embed: true
        })
      ])
      return {
        articleList: list.data,
        total: +list.headers['x-wp-total'],
        nCurrentPage: +params.id
      }
    } catch (err) {
      const code = err.response.data.data.status
      const message = err.response.data.message
      error({ statusCode: code, message })
      store.dispatch('updateError', { code, message })
    }
  },
  name: 'Article',
  methods: {
    currentPage (id) {
      this.$router.push({
        name: 'article-id-title',
        params: {
          id
        }
      })
    },

    // 上一页
    prevPage (id) {
      this.$router.push({
        name: 'article-id-title',
        params: {
          id
        }
      })
    },

    // 下一页
    nextPage (id) {
      this.$router.push({
        name: 'article-id-title',
        params: {
          id
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
// 文章列表
.container{
  padding: $container-padding;
  background: $color-white;
  border-radius: $border-radius;

  .header{
    padding-bottom: $container-padding;
    border-bottom: 1px solid $color-main-background;
    font-size: $font-size-large;
  }
}

@media screen and (max-width:767px) {
  // 文章列表
  .container{
    .article-list{
      flex-wrap: wrap;
      height: auto;

      .title{
        margin-top: 15px;
        font-size: $font-size-large;
      }

      .summary{
        height: auto;
      }

      .list-content{
        height: auto;
      }

      .opeartion{
        position: static;
        display: block;
        margin-top: 10px;
      }

      .details-btn{
        display: block;
        margin-top: 10px;
        padding: 10px 0;
        text-align: center;
      }
    }

    .thumbnail-wrap{
      width: 100%;
      margin-right: 0;
      text-align: center;

      .thumbnail{
        width: auto;
        height: auto;
        max-height: 150px;
      }
    }
  }

  // 翻页
  /deep/ .el-pagination{
    .el-pagination__jump{
      display: none;
    }
  }
}
</style>
