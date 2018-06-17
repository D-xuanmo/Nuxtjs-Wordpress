<template>
  <section class="container">
    <!-- 文章内容开始 -->
    <article class="section article">
      <h2 class="title">{{ article.title.rendered }}</h2>
      <div class="other-info">
        <span class="author">{{ article.articleInfor.author }}</span>
        <span class="text">&nbsp;发表于：</span>
        <span class="time"><i class="iconfont icon-time"></i> {{ article.date.replace('T', ' ') }}</span>
        <span class="text">&nbsp;分类：</span>
        <span class="classify" v-for="item in article.articleInfor.classify" :key="item.key">{{ item.name }}</span>&nbsp;
        <i class="iconfont icon-hot1">{{ article.articleInfor.viewCount }}</i>&nbsp;
        <i class="iconfont icon-message-f">{{ article.articleInfor.commentCount }}</i>
      </div>
      <div class="content" v-html="article.content.rendered"></div>
    </article>
    <!-- 文章内容结束 -->
    <div class="section operation">
      <!-- 点赞 -->
      <ul class="opinion">
        <li class="list" v-for="(item, key) in opinion" :key="item.key" @click="updateOpinion(key)">
          <span class="block total">
            <i class="iconfont icon-loading" v-show="item.isShowLaoding"></i> {{ xmLike[key] }}人
          </span>
          <img :src="item.src" width="40" alt="">
          <span class="block">{{ item.text }}</span>
        </li>
      </ul>
      <!-- 分享 -->
      <div class="share text-center">
        <span class="text">分享到：</span>
        <a :href="`http://connect.qq.com/widget/shareqq/index.html?url=${$store.state.info.baseUrl}&title=${article.title.rendered}&summary=`" target="_blank">
          <svg class="iconfont-colour" aria-hidden="true">
            <use xlink:href="#icon-QQ"></use>
          </svg>
        </a>
        <a :href="`http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=${$store.state.info.baseUrl}&title=${article.title.rendered}`" target="_blank">
          <svg class="iconfont-colour" aria-hidden="true">
            <use xlink:href="#icon-Qzone"></use>
          </svg>
        </a>
        <a href="#">
          <svg class="iconfont-colour" aria-hidden="true">
            <use xlink:href="#icon-weichat"></use>
          </svg>
        </a>
        <a :href="`http://service.weibo.com/share/share.php?url=${$store.state.info.baseUrl}%230-tsina-1-21107-397232819ff9a47a7b7e80a40613cfe1&title=${article.title.rendered}&appkey=1343713053&searchPic=true#_loginLayer_1473259217614`" target="_blank">
          <svg class="iconfont-colour" aria-hidden="true">
            <use xlink:href="#icon-xinlang"></use>
          </svg>
        </a>
      </div>
      <!-- 标签 -->
      <div class="tag-wrap text-center">
        <i class="iconfont icon-tag"></i>
        <span v-for="(item, index) in classify" :key="item.key">{{ index === classify.length - 1 ? item.name : `${item.name}、` }}</span>
      </div>
      <!-- 上一篇、下一篇 -->
      <div class="relative-link-wrap">
        <div class="prev">
          <p v-if="article.articleInfor.prevLink === ''">已是第一篇文章！</p>
          <p v-else>上一篇：<nuxt-link :to="{ name: 'details-id', params: { id: article.articleInfor.prevLink.ID } }">{{ article.articleInfor.prevLink.post_title }}</nuxt-link></p>
        </div>
        <div class="next">
          <p v-if="article.articleInfor.nextLink === ''">已是最后一篇文章！</p>
          <p v-else>下一篇：<nuxt-link :to="{ name: 'details-id', params: { id: article.articleInfor.nextLink.ID } }">{{ article.articleInfor.nextLink.post_title }}</nuxt-link></p>
        </div>
      </div>
    </div>
    <!-- 作者信息 -->
    <div class="section author-introduct">
      <!-- 头像 -->
      <img :src="article.articleInfor.other.authorPic.full" alt="" width="100">
      <div class="right">
        <!-- 昵称 -->
        <div class="header">
          <p class="inline-block name">
            作者简介：<i class="iconfont icon-about-f"></i><span class="f-s-14px">{{ article.articleInfor.author }}</span>
          </p>
          <p class="inline-block leave"></p>
        </div>
        <!-- 简介 -->
        <p class="author-summary">{{ article.articleInfor.other.authorTro }}</p>
        <!-- 社交信息 -->
        <ul class="author-link">
          <li class="list">
            <nuxt-link :to="{ name: 'index' }">
              <svg class="iconfont-colour" aria-hidden="true">
                <use xlink:href="#icon-icon-test"></use>
              </svg>
            </nuxt-link>
          </li>
          <li class="list" v-for="(item, key) in authorOtherInfo" :key="item.key" v-if="key === 'wechatNum'" @click="showWechatNum(item.url)">
            <a href="javascript:;">
              <svg class="iconfont-colour" aria-hidden="true">
                <use :xlink:href="item.icon"></use>
              </svg>
            </a>
          </li>
          <li class="list" v-else>
            <a :href="key == 'email' ? `mailto:${item.url}` : item.url">
              <svg class="iconfont-colour" aria-hidden="true">
                <use :xlink:href="item.icon"></use>
              </svg>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!-- 评论列表 -->
    <div class="section comment">
      <h2 class="comment-title" v-html="`共 ${article.articleInfor.commentCount} 条评论关于 “${article.title.rendered}”`"></h2>
      <no-ssr>
        <comments></comments>
      </no-ssr>
    </div>
  </section>
</template>
<script>
import axios from '~/plugins/axios'
import Comments from '~/components/comment/Index'
export default {
  async asyncData (context) {
    let [info, menu, article] = await Promise.all([
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/info`),
      axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/menu`),
      axios.get(`${process.env.baseUrl}/wp-json/wp/v2/posts/${context.route.params.id}`)
    ])
    return {
      info: info.data,
      menu: menu.data.mainMenu,
      article: article.data,
      classify: article.data.articleInfor.classify,
      xmLike: article.data.articleInfor.xmLike
    }
  },
  name: 'Article',
  components: {
    Comments
  },
  data () {
    return {
      opinion: {
        very_good: {
          src: 'https://upyun.xuanmo.xin/images/like_love.png',
          isShowLaoding: false,
          text: 'Love'
        },
        good: {
          src: 'https://upyun.xuanmo.xin/images/like_haha.png',
          isShowLaoding: false,
          text: 'Haha'
        },
        commonly: {
          src: 'https://upyun.xuanmo.xin/images/like_wow.png',
          isShowLaoding: false,
          text: 'Wow'
        },
        bad: {
          src: 'https://upyun.xuanmo.xin/images/like_sad.png',
          isShowLaoding: false,
          text: 'Sad'
        },
        very_bad: {
          src: 'https://upyun.xuanmo.xin/images/like_angry.png',
          isShowLaoding: false,
          text: 'Angry'
        }
      },
      authorOtherInfo: {
        github: {
          icon: '#icon-GitHub'
        },
        qq: {
          icon: '#icon-qq1'
        },
        wechatNum: {
          icon: '#icon-weixin5'
        },
        sina: {
          icon: '#icon-xinlang1'
        },
        email: {
          icon: '#icon-youxiang'
        }
      }
    }
  },
  created () {
    let other = this.article.articleInfor.other
    this.$store.commit('getInfo', {
      info: this.info,
      menu: this.menu
    })
    // 合并作者数据
    for (let key in this.authorOtherInfo) {
      this.authorOtherInfo[key].url = other[key]
    }
  },
  head () {
    return {
      title: this.article.title.rendered,
      meta: [
        { name: 'description', content: this.article.articleInfor.summary }
      ],
      link: [
        { rel: 'stylesheet', href: 'https://upyun.xuanmo.xin/css/prism.css' }
      ],
      script: [
        { src: 'https://upyun.xuanmo.xin/js/prism.js' }
      ]
    }
  },
  methods: {
    // 发表意见
    updateOpinion (key) {
      if (localStorage.getItem(`xm_link_${this.$route.params.id}`)) {
        this.$message({
          title: '您已经发表过意见了！',
          type: 'warning'
        })
      } else {
        this.opinion[key].isShowLaoding = true
        axios.post(`${process.env.baseUrl}/wp-json/xm-blog/v1/link/`, {
          params: {
            id: this.$route.params.id,
            key
          }
        }).then((res) => {
          this.opinion[key].isShowLaoding = false
          this.xmLike[key] = res.data
          // 设置点赞状态
          localStorage.setItem(`xm_link_${this.$route.params.id}`, true)
        }).catch((err) => console.log(err))
      }
    },

    // 显示微信号码
    showWechatNum (num) {
      this.$message({
        title: `微信号：${num}`,
        showClose: true,
        showImg: true,
        center: true,
        wrapCenter: true,
        width: 280,
        imgUrl: this.article.articleInfor.other.wechatPic
      })
    }
  },
  mounted () {
    // 代码高亮
    // if (process.browser) window.Prism.highlightAll()
  }
}
</script>
<style lang="scss" scoped>
.section{
  margin-top: $container-margin;
  padding: $container-padding;
  background: $color-white;
}

.article{
  margin-top: 0;

  .title{
    padding: 10px 0;
    font-size: 20px;
    text-align: center;
  }

  .other-info{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-border;
    text-align: center;
  }

  // 正文
  .content{
    line-height: 2;
  }
}

.opinion{
  display: flex;
  justify-content: center;
  margin-bottom: $container-margin;
  text-align: center;

  .list{
    margin: 0 20px;
    cursor: pointer;
  }

  .total{
    font-size: $font-size-small;
  }

  .icon-loading{
    vertical-align: baseline;
  }
}

// 分享按钮
.share{
  margin-bottom: $container-margin;

  .iconfont-colour{
    width: 25px;
    height: 25px;
    vertical-align: bottom;
    margin: 0 5px;
  }
}

// 文章标签
.tag-wrap{
  margin-bottom: $container-margin;

  .iconfont{
    margin-right: 3px;
    font-size: 20px;
  }
}

// 上一篇、下一篇
.relative-link-wrap{
  .next{
    margin-top: 10px;
  }
}

// 作者信息
.author-introduct{
  display: flex;
  justify-content: space-between;
  align-items: center;

  .right{
    flex: 1;
  }

  .header{
    margin-bottom: 5px;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-border;
  }

  .name{
    font-size: $font-size-large;
  }

  img{
    margin-right: 10px;
    border-radius: $border-radius;
  }
}

.author-link{
  display: flex;
  flex-wrap: wrap;
  margin-top: 10px;

  .list{
    box-sizing: border-box;
    margin-right: 10px;
    padding: 5px;
    border-radius: $border-radius;
    background: $color-sub-background;
    font-size: $font-size-small;
  }

  .iconfont-colour{
    width: 20px;
    height: 20px;
    vertical-align: middle;
  }
}

.comment-title{
  margin-bottom: 10px;
  padding: 10px 0;
  border-radius: $border-radius;
  background: $color-sub-background;
  font-size: $font-size-large;
  text-align: center;
}
</style>
