<template>
  <section class="container">
    <!-- 文章内容开始 -->
    <article class="section article">
      <h2 class="title">{{ article.title.rendered }}</h2>
      <div class="other-info">
        <span class="author">{{ article.articleInfor.author }}</span>
        <span class="text">&nbsp;发表于：</span>
        <span class="time"><i class="iconfont icon-time"></i>{{ article.date.replace('T', ' ') }}</span>
        <span class="text">&nbsp;分类：</span>
        <span class="classify" v-for="(item, index) in article.articleInfor.classify" :key="item.key" v-html="index === article.articleInfor.classify.length - 1 ? item.name : `${item.name}、`"></span>&nbsp;
        <span><i class="iconfont icon-hot1"></i>{{ article.articleInfor.viewCount }}</span>&nbsp;
        <span><i class="iconfont icon-message-f"></i>{{ article.articleInfor.commentCount }}</span>
      </div>
      <div class="content-details" v-html="article.content.rendered.replace(/https?:\/\/(\w+\.)+\w+:\d+/g, '')"></div>
    </article>
    <!-- 文章内容结束 -->
    <div class="section operation">
      <!-- 点赞 -->
      <ul class="opinion">
        <li class="list" v-for="(item, key) in opinion" :key="item.key" @click="updateOpinion(key)">
          <span class="block total">
            <i class="iconfont icon-loading" v-show="item.isShowLaoding"></i> {{ xmLike[key] }}人
          </span>
          <img :src="item.src" width="40" height="40" alt="">
          <span class="block">{{ item.text }}</span>
        </li>
      </ul>
      <!-- 分享 -->
      <div class="share text-center">
        <span class="text">分享到：</span>
        <a :href="`https://connect.qq.com/widget/shareqq/index.html?url=${$store.state.info.baseUrl}/details/${$route.params.id}&title=${article.title.rendered}&summary=`" target="_blank">
          <svg-icon iconName="#icon-QQ"></svg-icon>
        </a>
        <a :href="`https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=${$store.state.info.baseUrl}/details/${$route.params.id}&title=${article.title.rendered}&summary=${article.articleInfor.summary}`" target="_blank">
          <svg-icon iconName="#icon-Qzone"></svg-icon>
        </a>
        <a href="#">
          <svg-icon iconName="#icon-weichat"></svg-icon>
        </a>
        <a :href="`https://service.weibo.com/share/share.php?url=${$store.state.info.baseUrl}/details/${$route.params.id}%230-tsina-1-21107-397232819ff9a47a7b7e80a40613cfe1&title=${article.title.rendered}&appkey=1343713053&searchPic=true#_loginLayer_1473259217614`" target="_blank">
          <svg-icon iconName="#icon-xinlang"></svg-icon>
        </a>
      </div>
      <!-- 标签 -->
      <div class="tag-wrap text-center">
        <i class="iconfont icon-tag" v-show="tags.length"></i>
        <span v-for="(item, index) in tags" :key="item.key" v-html="index === tags.length - 1 ? item.name : `${item.name}、`"></span>
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
          <div class="reward" @click="isShowReward = true"><svg-icon iconName="#icon-dashang"></svg-icon>打赏</div>
          <!-- 打赏详情 -->
          <div v-if="isShowReward" class="reward-toast" @click="isShowReward = false">
            <div class="reward-toast-inner text-center" @click.stop="isShowReward = true">
              <i class="iconfont icon-close" @click.stop="isShowReward = false"></i>
              <p class="thumbnail"><img :src="article.articleInfor.other.authorPic.full" alt="" width="80"></p>
              <p class="summary">{{ $store.state.info.rewardText }}</p>
              <div class="reward-qrcode-wrap">
                <img v-if="reward" :src="$store.state.info.alipay" class="reward-qrcode" width="150" height="150" alt="">
                <img v-else :src="$store.state.info.wechatpay" class="reward-qrcode" width="150" height="150" alt="">
              </div>
              <div class="reward-btn">
                <el-radio v-model="reward" :label="true">
                  <img src="../../assets/images/alipay.svg" width="80" alt="">
                </el-radio>
                <el-radio v-model="reward" :label="false">
                  <img src="../../assets/images/wechatpay.svg" width="80" alt="">
                </el-radio>
              </div>
            </div>
          </div>
        </div>
        <!-- 简介 -->
        <p class="author-summary">{{ article.articleInfor.other.authorTro }}</p>
        <!-- 社交信息 -->
        <ul class="author-link">
          <li class="list">
            <nuxt-link :to="{ name: 'index' }">
              <svg-icon iconName="#icon-icon-test"></svg-icon>
            </nuxt-link>
          </li>
          <li class="list" v-for="(item, key) in authorOtherInfo" :key="item.key" v-if="key === 'wechatNum'" @click="showWechatNum(item.url)">
            <a href="javascript:;">
              <svg-icon :iconName="item.icon"></svg-icon>
            </a>
          </li>
          <li class="list" v-else>
            <a :href="key == 'email' ? `mailto:${item.url}` : item.url">
              <svg-icon :iconName="item.icon"></svg-icon>
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
import API from '~/api'
import Comments from '~/components/comment/Index'
export default {
  async asyncData ({ params }) {
    let [article, viewCount] = await Promise.all([
      API.getArticleDetails(params.id),
      // 更新阅读量
      API.updateViewCount({ id: params.id })
    ])
    return {
      article: article.data,
      classify: article.data.articleInfor.classify,
      tags: article.data.articleInfor.tags,
      xmLike: article.data.articleInfor.xmLike,
      viewCount: viewCount.data
    }
  },
  name: 'Details',
  components: {
    Comments
  },
  data () {
    return {
      reward: true,
      isShowReward: false,
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

    // 更新阅读量
    this.article.articleInfor.viewCount = this.viewCount

    // 合并作者数据
    for (let key in this.authorOtherInfo) {
      this.authorOtherInfo[key].url = other[key]
    }
  },
  head () {
    let keywords = []
    this.tags && this.tags.forEach(item => keywords.push(item.name))
    return {
      title: `${this.article.title.rendered} | ${this.$store.state.info.blogName}`,
      meta: [
        { hid: 'keywords', name: 'keywords', content: keywords.join(',') },
        { hid: 'description', name: 'description', content: this.article.articleInfor.summary }
      ],
      style: [
        { cssText: this.$store.state.info.detailsCss, type: 'text/css' }
      ],
      link: [
        { hid: 'prism', rel: 'stylesheet', href: 'https://upyun.xuanmo.xin/css/prism.css' }
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
        API.updateArticleLike({
          id: this.$route.params.id,
          key
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
        imgUrl: this.article.articleInfor.other.wechatPic,
        duration: 0
      })
    }
  },
  mounted () {
    process.browser && document.querySelectorAll('pre code').forEach(block => Prism.highlightElement(block))
  }
}
</script>
<style lang="scss" scoped>
.section{
  margin-top: $container-margin;
  padding: $container-padding;
  background: $color-white;
  border-radius: $border-radius;
}

.reward-toast {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 999999;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,.5);

  .reward-toast-inner {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 400px;
    height: 400px;
    background: #fff;
    border-radius: 5px;
    transform: translate(-50%, -50%);
  }

  .icon-close {
    position: absolute;
    top: 15px;
    right: 15px;
    cursor: pointer;
  }

  .summary {
    font-size: 18px;
  }

  .thumbnail {
    padding: 30px 0 20px;

    img {
      border-radius: 50%;
    }
  }

  .reward-qrcode-wrap {
    margin: 15px 0;
  }

  .reward-btn {
    img {
      vertical-align: middle;
    }
  }
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

    .iconfont{
      vertical-align: baseline;
    }
  }

  // 正文
  .content-details{
    line-height: 2;
    word-break: break-all;

    /deep/ a{
      color: $color-theme;
    }

    /deep/ h2{
      margin-top: 10px;
      font-weight: bold;

      .iconfont{
        vertical-align: bottom;
      }
    }

    /deep/ h1,
    /deep/ h2{
      font-size: $font-size-large;
      font-weight: bold;
    }

    /deep/ h3{
      font-weight: bold;
    }
    /deep/ h4,
    /deep/ h5,
    /deep/ h6{
      font-size: $font-size-small;
      font-weight: bold;
    }

    /deep/ img{
      height: auto !important;
      box-shadow: 0 0 10px #d2d2d2;
    }
  }
}

.opinion{
  display: flex;
  justify-content: center;
  margin-bottom: $container-margin;
  text-align: center;

  .list{
    width: 50px;
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
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-border;
  }

  .reward {
    margin-left: 10px;
    cursor: pointer;

    .iconfont-colour {
      vertical-align: bottom;
    }
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
    margin-bottom: 5px;
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

// 代码高亮
/deep/ div.code-toolbar{
  overflow: hidden;
  width: 100%;
  margin: 20px 0;
  padding-top: 30px;
  border-radius: $border-radius;

  pre{
    margin: 0;
    border: {
      width: 0 1px 1px;
      style: solid;
      color: $color-main-background;
    }
  }

  code{
    border: 0;
    box-shadow: none;
    background-size: 4em 4em;
    line-height: 2;
  }

  .line-numbers .line-numbers-rows{
    border-color: $color-main-background;
  }

  .toolbar{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 30px;
    background: #358ccb;
    opacity: 1;

    span{
      background: none;
      box-shadow: none;
      border-radius: 0;
      color: #fff;
    }
  }

  .toolbar-item{
    padding-left: 20px;

    span:hover{
      color: $color-white;
    }

    &:before{
      content: "\e7ae";
      font-family: "iconfont";
      color: $color-white;
    }
  }
}

@media screen and (max-width:767px) {
  .opinion{
    justify-content: space-between;

    .list{
      margin: 0;
    }
  }
}
</style>
