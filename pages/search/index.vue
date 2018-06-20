<template>
  <div class="container">
    <ul class="header">
      <li class="list">共<span class="mark">{{ total }}</span>条关于“<span class="mark">{{ $route.query.s }}</span>”的文章</li>
    </ul>
    <article class="article-list" v-for="item in articleList" :key="item.key">
      <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }">
        <img :src="item.articleInfor.thumbnail === null ? $store.state.info.setExtend.thumbnail : item.articleInfor.thumbnail.replace(/https?:\/\/.+\:\d+/, '')" class="thumbnail" alt="">
      </nuxt-link>
      <div class="list-content">
        <h2 class="title">
          <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }">{{ item.title.rendered }}</nuxt-link>
        </h2>
        <p class="summary">{{ item.articleInfor.summary }}</p>
        <div class="opeartion">
          <div class="information">
            <span><i class="iconfont icon-time"></i>{{ item.date.replace('T', ' ') }}</span>
            <span><i class="iconfont icon-eye"></i>{{ item.articleInfor.viewCount }}</span>
            <span><i class="iconfont icon-message"></i>{{ item.articleInfor.commentCount }}</span>
            <span><i class="iconfont icon-zan"></i>{{ item.articleInfor.xmLike.very_good }}</span>
          </div>
          <nuxt-link class="details-btn" :to="{ name: 'details-id', params: { id: item.id } }">阅读详情</nuxt-link>
        </div>
      </div>
    </article>
    <!-- more btn start -->
    <el-pagination
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
import axios from '~/plugins/axios'
export default {
  watchQuery: ['page', 's'],
  async asyncData ({ query }) {
    let [info, menu, list] = await Promise.all([
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/info`),
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/menu`),
      axios.get(`${process.env.baseUrl}/wp-json/wp/v2/posts`, {
        params: {
          search: query.s,
          page: query.page,
          per_page: 8,
          _embed: true
        }
      })
    ])
    return {
      info: info.data,
      menu: menu.data.mainMenu,
      articleList: list.data,
      total: +list.headers['x-wp-total'],
      nCurrentPage: +query.page
    }
  },
  head () {
    return {
      title: `关于“${this.$route.query.s}”的文章`
    }
  },
  name: 'Search',
  created () {
    this.$store.commit('getInfo', {
      info: this.info,
      menu: this.menu
    })
  },
  methods: {
    currentPage (n) {
      this.$router.push({
        name: 'search',
        query: {
          page: n,
          s: this.$route.query.s
        }
      })
    },

    // 上一页
    prevPage (n) {
      this.$router.push({
        name: 'search',
        query: {
          page: n,
          s: this.$route.query.s
        }
      })
    },

    // 下一页
    nextPage (n) {
      this.$router.push({
        name: 'search',
        query: {
          page: n,
          s: this.$route.query.s
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

    .mark{
      color: $color-highlight-text;
    }
  }

  // 文章列表
  .article-list{
    display: flex;
    justify-content: space-between;
    height: 150px;
    padding: 20px 0;
    border-bottom: 1px solid $color-main-background;

    // 缩略图
    .thumbnail{
      width: 240px;
      height: 150px;
      border-radius: $border-radius;
      transition: .5s;

      &:hover{
        transform: scale(1.05);
      }
    }

    .list-content{
      position: relative;
      width: 610px;
    }

    .title{
      margin-bottom: 10px;
      font-size: 18px;

      a{
        color: #333;

        &:hover{
          color: $color-highlight-text;
        }
      }
    }

    .summary{
      display: -webkit-box;
      overflow: hidden;
      height: 80px;
      -webkit-box-orient: vertical;
      word-break: break-all;
      text-overflow: ellipsis;
      -webkit-line-clamp: 3;
      line-height: 1.8;
    }

    // 文章信息
    .opeartion{
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      position: absolute;
      bottom: 0;
      width: 100%;
      font-size: $font-size-small;
      color: $color-golden;
    }

    .information{
      span{
        margin-right: 10px;
      }

      .iconfont{
        margin-right: 2px;
      }
    }

    .details-btn{
      padding: 5px 10px;
      background: $color-highlight-text;
      border-radius: $border-radius;
      color: $color-white;
    }
  }
}
</style>
