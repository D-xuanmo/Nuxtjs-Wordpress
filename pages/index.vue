<template>
  <div class="container">
    <app-header/>
    <div class="wrap">
      <div class="left-content">
        <!-- banner start -->
        <div class="banner-wrap">
          <ul class="big-banner">
            <li class="list">
              <nuxt-link to="/">
                <img src="https://www.qiniu.com/assets/invite/banner-bf1febd06635739c4d241bdb1b683bc798e9abf06555cdda47245f32389d51fa.png" alt="">
                <span class="title">标题文字</span>
              </nuxt-link>
            </li>
          </ul>
          <ul class="small-banner">
            <li class="list">
              <nuxt-link to="/">
                <img src="http://cdn.itarea.cn/wp-content/uploads/2018/05/maxresdefault.jpg" alt="">
                <span class="title">标题文字</span>
              </nuxt-link>
            </li>
            <li class="list">
              <nuxt-link to="/">
                <img src="http://cdn.itarea.cn/wp-content/uploads/2018/05/maxresdefault.jpg" alt="">
                <span class="title">标题文字</span>
              </nuxt-link>
            </li>
            <li class="list">
              <nuxt-link to="/">
                <img src="http://cdn.itarea.cn/wp-content/uploads/2018/05/maxresdefault.jpg" alt="">
                <span class="title">标题文字标题文字标题文字标题文字标题文字标题文字标题文字</span>
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
            <nuxt-link :to="{ name: 'article-id', params: { id: item.id } }">
              <img :src="item.articleInfor.thumbnail" alt="">
            </nuxt-link>
            <div class="list-content">
              <h2 class="title">
                <nuxt-link :to="{ name: 'article-id', params: { id: item.id } }">{{ item.title.rendered }}</nuxt-link>
              </h2>
              <p class="summary">{{ item.articleInfor.summary }}...</p>
              <div class="opeartion">
                <div class="information">
                  <span><i class="iconfont icon-time"></i>{{ item.date.replace('T', ' ') }}</span>
                  <span><i class="iconfont icon-eye"></i>{{ item.articleInfor.viewCount }}</span>
                  <span><i class="iconfont icon-message"></i>{{ item.articleInfor.commentCount }}</span>
                  <span><i class="iconfont icon-zan"></i>{{ item.articleInfor.xmLink.very_good }}</span>
                </div>
                <nuxt-link class="details-btn" :to="{ name: 'article-id', params: { id: item.id } }">阅读详情</nuxt-link>
              </div>
            </div>
          </article>
          <!-- more btn start -->
          <div class="more-btn-wrap">
            <button class="btn active" @click="isCLick && getMoreList()"><i v-show="isShowLoading" class="iconfont icon-loading"></i> {{ btnText }}</button>
          </div>
          <!-- more btn end -->
        </div>
        <!-- article list end -->
      </div>
      <!-- sidebar start -->
      <app-sidebar/>
      <!-- sidebar end -->
    </div>
    <app-footer/>
  </div>
</template>

<script>
import axios from '~/plugins/axios'
import AppHeader from '~/components/AppHeader'
import AppFooter from '~/components/AppFooter'
import AppSidebar from '~/components/AppSidebar'
export default {
  async asyncData ({ params }) {
    let { data } = await axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/info`)
    return { info: data }
  },
  name: 'Index',
  components: {
    AppHeader,
    AppFooter,
    AppSidebar
  },
  data () {
    return {
      isShowLoading: true,
      isCLick: true,
      currentPage: 1,
      articleList: [],
      btnText: '点击加载更多'
    }
  },
  created () {
    this.init()
    console.log(process.env.NODE_ENV)
    this.$store.commit('getInfo', this.info)
  },
  methods: {
    // 获取第一页数据
    init () {
      axios.get('/wp-json/wp/v2/posts', {
        params: {
          page: this.currentPage,
          per_page: 5,
          _embed: true
        }
      }).then(res => {
        this.articleList = [...this.articleList, ...res.data]
        if (this.currentPage === +res.header['x-wp-totalpages']) {
          this.btnText = '我是有底线的^_^'
          this.isCLick = false
          this.isShowLoading = false
          return
        }
        this.isShowLoading = false
        this.isCLick = true
      }).catch(err => console.log(err))
    },

    // 翻页
    getMoreList () {
      this.isShowLoading = true
      this.isCLick = false
      this.currentPage++
      this.init()
    }
  }
}
</script>

<style lang="scss" scoped>
.wrap{
  display: flex;
  justify-content: space-between;
  margin-top: $container-margin;

  // 左边内容容器
  .left-content{
    width: 900px;
  }

  // 侧边栏
  .sidebar-wrap{
    width: 280px;
  }
}

// banner
.banner-wrap{
  display: flex;
  justify-content: space-between;
  height: 320px;

  img{
    vertical-align: top;
    width: 100%;
    height: 100%;
    border-radius: $border-radius;
  }

  .list{
    position: relative;

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
    height: 150px;
    padding: 20px 0;
    border-bottom: 1px solid $color-main-background;

    // 缩略图
    img{
      width: 240px;
      height: 150px;
      border-radius: $border-radius;
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
