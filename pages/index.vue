<template>
  <div class="container">
    <!-- banner start -->
    <div class="banner-wrap">
      <div class="big-banner">
        <nuxt-link class="list block" :to="$store.state.info.banner.big_banner.link">
          <img :src="$store.state.info.banner.big_banner.path" alt="">
          <span class="title" :title="$store.state.info.banner.big_banner.text" v-show="$store.state.info.banner.big_banner.text">{{ $store.state.info.banner.big_banner.text }}</span>
        </nuxt-link>
      </div>
      <ul class="small-banner">
        <li class="list" v-for="item in $store.state.info.banner.small_banner" :key="item.key">
          <nuxt-link class="block" :to="item.link">
            <img :src="item.path" alt="">
            <span v-show="item.text" class="title" :title="item.text">{{ item.text }}</span>
          </nuxt-link>
        </li>
      </ul>
    </div>
    <!-- banner end -->
    <!-- article list start -->
    <div class="article-list-wrap">
      <ul class="header">
        <li class="list">最新文章</li>
      </ul>
      <article class="article-list" v-for="item in articleList" :key="item.key">
        <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" class="thumbnail-wrap">
          <img :src="item.articleInfor.thumbnail === null ? $store.state.info.setExtend.thumbnail : item.articleInfor.thumbnail.replace(/https?:\/\/.+\:\d+/, '')" class="thumbnail" alt="">
        </nuxt-link>
        <div class="list-content">
          <h2 class="title">
            <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" v-html="item.title.rendered"></nuxt-link>
          </h2>
          <p class="summary">{{ item.articleInfor.summary }}</p>
          <div class="opeartion">
            <div class="information">
              <span><x-icon type="icon-time"></x-icon>{{ item.date.replace('T', ' ') }}</span>
              <span><x-icon type="icon-eye"></x-icon>{{ item.articleInfor.viewCount }}</span>
              <span><x-icon type="icon-message"></x-icon>{{ item.articleInfor.commentCount }}</span>
              <span><x-icon type="icon-zan"></x-icon>{{ item.articleInfor.xmLike.very_good }}</span>
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
    <!-- article list end -->
  </div>
</template>

<script>
import API from '~/api'
export default {
  async asyncData ({ params, error, store }) {
    try {
      let [list] = await Promise.all([
        API.getArticleList({
          page: 1,
          per_page: 8,
          _embed: true
        })
      ])
      return {
        articleList: list.data,
        total: +list.headers['x-wp-total'],
        nCurrentPage: 1
      }
    } catch (err) {
      const code = err.response.data.data.status
      const message = err.response.data.message
      error({ statusCode: code, message })
      store.dispatch('updateError', { code, message })
    }
  },
  head () {
    return {
      title: `${this.$store.state.info.blogName} | ${this.$store.state.info.blogDescription}`,
      meta: [
        { name: 'keywords', content: this.$store.state.info.setExtend.keywords },
        { name: 'description', content: this.$store.state.info.setExtend.description }
      ]
    }
  },
  name: 'Index',
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
// banner
.banner-wrap{
  display: flex;
  justify-content: space-between;
  height: 320px;

  img{
    vertical-align: top;
    width: 100%;
    height: 100%;
  }

  .list{
    overflow: hidden;
    position: relative;
    border-radius: $border-radius;

    .title{
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 30px;
      background: rgba(0,0,0,.3);
      text-indent: $font-size-base;
      line-height: 30px;
      color: $color-white;
      @extend %ellipsis;
    }
  }

  .big-banner{
    width: 710px;

    img{
      height: 320px;
    }
  }

  .small-banner{
    width: 180px;

    .list{
      height: 100px;
      margin-bottom: 10px;

      &:last-of-type{
        margin-bottom: 0;
      }
    }
  }
}

@media screen and (max-width: 1024px) {
  .banner-wrap{
    flex-wrap: wrap;
    height: auto;

    .big-banner{
      width: 100%;

      img{
        height: auto;
      }
    }

    .small-banner{
      display: flex;
      justify-content: space-between;
      width: 100%;
      margin-top: $container-margin;

      .list{
        width: 32%;
        height: auto;
        margin-bottom: 0;
      }

      img{
        height: auto;
      }
    }
  }
}

@media screen and (max-width:767px) {
  .banner-wrap .list .title {
    height: 20px;
    font-size: 10px;
    line-height: 20px;
    text-indent: 8px;
  }
  // 文章列表
  .article-list-wrap{
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
