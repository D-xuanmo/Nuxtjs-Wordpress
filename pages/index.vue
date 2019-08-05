<template>
  <div class="container">
    <!-- banner start -->
    <div
      ref="bannerWrapper"
      :class="[
        'banner-wrap',
        info.banner.style === '1' && 'style-1',
        info.banner.style === '2' && 'style-2'
      ]"
    >
      <template v-if="info.banner.style === '1'">
        <div class="big-banner">
          <a class="list block" :href="info.banner.big.link">
            <img :src="info.banner.big.path" alt="">
            <span
              class="title"
              :title="info.banner.big.text"
              v-show="info.banner.big.text">
              {{ info.banner.big.text }}
            </span>
          </a>
        </div>
        <ul class="small-banner">
          <li class="list" v-for="item in info.banner.small" :key="item.key">
            <a class="block" :href="item.link">
              <img :src="item.path" alt="">
              <span v-show="item.text" class="title" :title="item.text">{{ item.text }}</span>
            </a>
          </li>
        </ul>
      </template>
      <template v-else-if="info.banner.style === '2'">
        <el-carousel trigger="click" :height="bannerHeight" :autoplay="false">
          <el-carousel-item v-for="(item, i) in info.banner.list" :key="i">
            <a :href="item.link" class="block">
              <img :src="item.path" alt="">
              <h3 class="title">{{ item.text }}</h3>
            </a>
          </el-carousel-item>
        </el-carousel>
      </template>
    </div>
    <!-- banner end -->
    <div v-if="info.notice" class="sidebar-list notice tablet-show">
      <div class="header">
        <p>
          <x-icon type="icon-notice2"></x-icon> 公告
        </p>
      </div>
      <div class="content" v-html="info.notice"></div>
    </div>
    <!-- article list start -->
    <div class="article-list-wrap">
      <ul class="header">
        <li class="list">最新文章</li>
      </ul>
      <article class="article-list" v-for="item in articleList" :key="item.key">
        <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" class="thumbnail-wrap">
          <img :src="item.articleInfor.thumbnail" class="thumbnail" alt="">
        </nuxt-link>
        <div class="list-content">
          <h2 class="title">
            <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" v-html="item.title.rendered"></nuxt-link>
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
    <!-- article list end -->
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'Index',
  fetch ({ store }) {
    store.commit('article/SET_CURRENT_PAGE', 1)
    return store.dispatch('article/getArticleList', {
      page: 1,
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
      title: `${this.info.blogName} | ${this.info.blogDescription}`,
      meta: [
        { name: 'keywords', content: this.info.keywords },
        { name: 'description', content: this.info.description }
      ]
    }
  },
  data () {
    return {
      bannerHeight: '405px'
    }
  },
  mounted () {
    this._bannerClacHeight()
    window.addEventListener('resize', this._bannerClacHeight)
  },
  beforeDestroy () {
    window.removeEventListener('resize', this._bannerClacHeight)
  },
  methods: {
    _bannerClacHeight () {
      this.bannerHeight = `${this.$refs.bannerWrapper.offsetWidth / (900 / 405)}px`
    },
    _changePagination (id) {
      this.$router.push({
        name: 'article-id-title',
        params: { id }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
// banner
.banner-wrap {
  position: relative;

  &.style-1 {
    display: flex;
    justify-content: space-between;
    height: 320px;
  }

  &.style-2 {
    background-color: #fff;
    .banner-title {
      position: absolute;
      bottom: 0;
      left: 0;
      min-width: 200px;
      max-width: 100%;
      padding: 5px 15px;
      box-sizing: border-box;
      background: rgba(0,0,0,.3);
      opacity: .9;
      @extend %ellipsis;
      color: #fff;
    }

    /deep/ .el-carousel__arrow {
      background-color: transparent;
      font-size: 30px;
    }

    /deep/ .el-carousel__indicators--horizontal {
      bottom: 20px;
    }
  }

  .title {
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

  img {
    vertical-align: top;
    width: 100%;
    height: 100%;
  }

  .list {
    overflow: hidden;
    position: relative;
    border-radius: $border-radius;
  }

  .big-banner {
    width: 710px;

    img {
      height: 320px;
    }
  }

  .small-banner {
    width: 180px;

    .list {
      height: 100px;
      margin-bottom: 10px;

      &:last-of-type {
        margin-bottom: 0;
      }
    }
  }
}

@media screen and (max-width: 1024px) {
  .banner-wrap {
    &.style-1 {
      flex-wrap: wrap;
      height: auto;

      .big-banner {
        width: 100%;

        img {
          height: auto;
        }
      }

      .small-banner {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: $container-margin;

        .list {
          width: 32%;
          height: auto;
          margin-bottom: 0;
        }

        img {
          height: auto;
        }
      }
    }

    &.style-2 {
      /deep/ .el-carousel__arrow {
        display: block !important;
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
  .article-list-wrap {
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
