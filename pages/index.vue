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
            <span class="title" :title="item.text">{{ item.text }}</span>
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
    <!-- article list end -->
  </div>
</template>

<script>
import axios from '~/plugins/axios'
export default {
  async asyncData ({ params }) {
    let [list] = await Promise.all([
      axios.get(`${process.env.baseUrl}/wp-json/wp/v2/posts`, {
        params: {
          page: 1,
          per_page: 8,
          _embed: true
        }
      })
    ])
    return {
      articleList: list.data,
      total: +list.headers['x-wp-total'],
      nCurrentPage: 1
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
    currentPage (n) {
      this.$router.push({
        name: 'article-id-title',
        params: {
          id: n
        }
      })
    },

    // 上一页
    prevPage (n) {
      this.$router.push({
        name: 'article-id-title',
        params: {
          id: n
        }
      })
    },

    // 下一页
    nextPage (n) {
      this.$router.push({
        name: 'article-id-title',
        params: {
          id: n
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

// 文章列表
.article-list-wrap{
  margin-top: $container-margin;
  padding: $container-padding;
  border-radius: $border-radius;
  background: $color-white;

  .header{
    padding-bottom: $container-padding;
    border-bottom: 1px solid $color-main-background;
    font-size: $font-size-large;
  }

  // 文章列表
  .article-list{
    display: flex;
    justify-content: space-between;
    height: 145px;
    padding: 20px 0;
    border-bottom: 1px solid $color-main-background;

    // 缩略图
    .thumbnail{
      width: 260px;
      height: 145px;
      border-radius: $border-radius;
      transition: .5s;

      &:hover{
        transform: scale(1.05);
      }
    }

    .list-content{
      position: relative;
      width: 590px;
    }

    .title{
      margin-bottom: 10px;
      font-size: 18px;

      a{
        color: #333;

        &:hover{
          color: $color-theme;
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
      background: $color-theme;
      border-radius: $border-radius;
      color: $color-white;
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
