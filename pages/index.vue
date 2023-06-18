<template>
  <div class="container">
    <!-- banner start -->
    <div
      ref="bannerWrapper"
      :class="[
        'banner-wrap',
        globalConfig.banner.style === '1' && 'style-1',
        globalConfig.banner.style === '2' && 'style-2'
      ]"
    >
      <template v-if="globalConfig.banner.style === '1'">
        <div class="big-banner">
          <a class="list block" :href="globalConfig.banner.big.link">
            <img :src="globalConfig.banner.big.path" class="img-hover">
            <span
              class="title"
              :title="globalConfig.banner.big.text"
              v-if="globalConfig.banner.big.text">
              {{ globalConfig.banner.big.text }}
            </span>
          </a>
        </div>
        <ul class="small-banner">
          <li class="list" v-for="item in globalConfig.banner.small" :key="item.key">
            <a class="block" :href="item.link">
              <img :src="item.path" class="img-hover">
              <span v-if="item.text" class="title" :title="item.text">{{ item.text }}</span>
            </a>
          </li>
        </ul>
      </template>
      <template v-else-if="globalConfig.banner.style === '2'">
        <el-carousel trigger="click" :height="bannerHeight" :autoplay="false">
          <el-carousel-item v-for="(item, i) in globalConfig.banner.list" :key="i">
            <a :href="item.link" class="block">
              <img :src="item.path" alt="">
              <h3 class="title">{{ item.text }}</h3>
            </a>
          </el-carousel-item>
        </el-carousel>
      </template>
    </div>
    <!-- banner end -->
    <div v-if="globalConfig.notice" class="sidebar-list notice tablet-show">
      <div class="header">
        <p>
          <x-icon type="icon-notice2" /> 公告
        </p>
      </div>
      <div class="content" v-html="globalConfig.notice"></div>
    </div>
    <!-- article list start -->
    <div class="article-list-wrap">
      <ul class="header">
        <li class="list">最新文章</li>
      </ul>
      <article class="article-list" v-for="item in articleList" :key="item.key">
        <nuxt-link v-if="item.articleInfor.thumbnail" :to="{ name: 'details-id', params: { id: item.id } }" class="thumbnail-wrap">
          <img :src="item.articleInfor.thumbnail" class="thumbnail" alt="">
        </nuxt-link>
        <div class="list-content">
          <h2 class="title" :class="!item.articleInfor.thumbnail && 'no-thumbnail'">
            <span class="classify" v-html="item.articleInfor.classify[0].name"></span>
            <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" class="vertical-middle" v-html="item.title.rendered"></nuxt-link>
          </h2>
          <p class="summary">{{ item.articleInfor.summary }}</p>
          <div class="opeartion">
            <div class="information">
              <span><x-icon type="icon-date" />{{ item.date }}</span>
              <span><x-icon type="icon-hot1" />{{ item.articleInfor.viewCount }}</span>
              <span><x-icon type="icon-message" />{{ item.articleInfor.commentCount }}</span>
              <span><x-icon type="icon-good" />{{ item.articleInfor.xmLike.very_good }}</span>
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
import XIcon from '../components/Icon/main.vue'
import {
  Carousel as ElCarousel,
  CarouselItem as ElCarouselItem,
  Pagination as ElPagination
} from 'element-ui'
export default {
  name: 'Index',
  components: { XIcon, ElCarousel, ElCarouselItem, ElPagination },
  fetch ({ store }) {
    store.commit('article/SET_CURRENT_PAGE', 1)
    return store.dispatch('article/getArticleList', {
      page: 1,
      per_page: 8,
      _embed: true
    })
  },
  computed: {
    ...mapState(['globalConfig']),
    ...mapState('article', ['articleList', 'totalPage', 'currentPage'])
  },
  head () {
    return {
      title: `${this.globalConfig.blogName} | ${this.globalConfig.blogDescription}`,
      meta: [
        { name: 'keywords', content: this.globalConfig.keywords },
        { name: 'description', content: this.globalConfig.description }
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
    overflow: hidden;
    background-color: #fff;
    border-radius: $border-radius;

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

    ::v-deep .el-carousel__arrow {
      background-color: transparent;
      font-size: 30px;
    }

    ::v-deep .el-carousel__indicators--horizontal {
      bottom: 20px;
    }
  }

  .title {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 30px;
    padding: 0 10px;
    box-sizing: border-box;
    background: rgba(0,0,0,.3);
    line-height: 30px;
    color: #fff;
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
    background-color: #fff;
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
      ::v-deep .el-carousel__arrow {
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
  }
}
</style>
