<template>
  <div class="comments-wrap">
    <!-- 发表评论 -->
    <div class="comment-from">
      <h3 class="comment-title">发表评论</h3>
      <p class="comment-sub-title">电子邮件地址不会被公开。 必填项已用<i class="c-red">*</i>标注</p>
      <!-- 评论其他功能 -->
      <div class="comment-other">
        <ul class="list-wrap">
          <li class="list" @click="showChartlet">
            <x-icon type="icon-upload-img2"></x-icon>贴图
          </li>
          <li class="list" @click.stop="getExpression">
            <x-icon type="icon-expression"></x-icon>表情
          </li>
        </ul>
        <upload-img v-show="showChart" @showChart="getShowChart" @updateContent="getContent" :showChart="showChart"></upload-img>
        <!-- 表情容器 -->
        <div class="expression-wrap" ref="expression">
          <a
            href="javascript:;"
            v-for="(item, key) in expression.content"
            :key="item.key"
            :title="item.title"
            :data-title="`[${key}]`"
            @click.stop="editExpression($event)">
            <img :src="item.url" :alt="item.title" width="30">
          </a>
        </div>
      </div>
      <!-- 评论输入框 -->
      <div class="comment-form-content">
        <label for="content">内容<i class="c-red">*</i></label>
        <textarea id="content" name="content" v-model="content.value" rows="8" cols="80" @keyup="contentValidate"></textarea>
        <span v-show="content.validate" class="comment-tips">{{ content.msg }}</span>
      </div>
      <div class="box">
        <div class="comment-inp comment-from-author">
          <label for="author">昵称<i class="c-red">*</i></label>
          <input
            type="text"
            id="author"
            name="author"
            v-model="author.value"
            value=""
            autocomplete="off"
            @keyup="authorValidate"
          >
          <span v-show="author.validate" class="comment-tips">{{ author.msg }}</span>
        </div>
        <div class="comment-inp comment-from-email">
          <label for="email">邮箱<i class="c-red">*</i></label>
          <input
            type="email"
            id="email"
            name="email"
            v-model="email.value"
            value=""
            autocomplete="off"
            @keyup="emailValidate"
          >
          <span v-show="email.validate" class="comment-tips">{{ email.msg }}</span>
        </div>
        <div class="comment-inp comment-from-url">
          <label for="url">网址</label>
          <input
            type="url"
            id="url"
            name="url"
            autocomplete="off"
            v-model="url.value"
          >
        </div>
        <div class="comment-inp comment-from-code">
          <label for="img-code">验证码<i class="c-red">*</i></label>
          <input
            type="number"
            id="img-code"
            name="img-code"
            value=""
            v-model="imgCode.value"
            autocomplete="off"
            @keyup="codeValidate"
          >
          <canvas width="240" height="60" class="canvas-img-code" @click="randomCode"></canvas>
          <span v-show="imgCode.validate" class="comment-tips">{{ imgCode.msg }}</span>
        </div>
      </div>
      <!-- 提交按钮 -->
      <div class="submit-wrap" @click.prevent="bSubmit && postComment()">
        <i v-show="!bSubmit" class="iconfont icon-loading"></i>
        <input type="submit" class="submit-btn" :value="submitText">
      </div>
    </div>
    <!-- 评论列表 -->
    <ul class="comment-list-wrap">
      <li class="comment-list" v-for="item in commentList" :key="item.key">
        <template>
          <img
            v-if="$store.state.info.isTextThumbnail === 'off'"
            :src="item.userAgentInfo.author_avatar_urls"
            class="list-gravatar"
            width="60"
            height="60"
            alt="">
          <p
            v-else-if="$store.state.info.isTextThumbnail === 'on'"
            class="list-gravatar-text"
            :style="{ background: item.userAgentInfo.background }">
            {{ item.author_name.substr(0, 1) }}
          </p>
        </template>
        <div class="list-header">
          <a :name="`comment-${item.id}`"></a>
          <a :href="item.author_url" target="_blank" class="author">{{ item.author_name }}</a>
          <!-- 评论者等级 -->
          <p class="inline-block">
            <span class="icon-vip icon-level" :class="[item.userAgentInfo.vipStyle.style, item.userAgentInfo.vipStyle.level]" :style="`background-image: url(https://upyun.xuanmo.xin/blog/vip.png);`" :title="item.userAgentInfo.vipStyle.title"></span>
            <svg v-if="item.userAgentInfo.vipStyle.title === '博主'" class="iconfont-colour admin" aria-hidden="true">
              <use xlink:href="#icon-vip"></use>
            </svg>
          </p>
          <p class="inline-block system-wrap">
            <!-- 浏览器logo -->
            <span
              v-if="item.userAgentInfo.userAgent.browserName"
              class="browser-info" :class="item.userAgentInfo.userAgent.browserName.toLowerCase()">
                {{ item.userAgentInfo.userAgent.browserName.replace('-', ' ') }} {{ item.userAgentInfo.userAgent.browserVersion }}
            </span>
            <span v-else class="browser-info">Unkonw</span>
            <!-- 系统logo -->
            <span class="system-info">{{ item.userAgentInfo.userAgent.system && item.userAgentInfo.userAgent.system.replace(/_/g, '.') }}</span>
          </p>
          <time>{{ item.date.replace('T', ' ') }}</time>
          <span v-if="item.status === 'hold'">您的评论正在审核中...</span>
        </div>
        <div class="list-content" v-html="item.content.rendered"></div>
        <!-- <div class="list-btn-wrap">
          <a href="#">回复</a>
        </div> -->
      </li>
    </ul>
    <div class="more-btn" @click="bClick && getMoreList()">
      <span><i v-show="bMoreList" class="iconfont icon-loading"></i>{{ sMoreBtnText }}</span>
    </div>
  </div>
</template>
<script>
import API from '~/api'
import uploadImg from './upload'
import { mapState } from 'vuex'
export default {
  name: 'Comments',
  components: {
    uploadImg
  },
  data () {
    return {
      commentList: [],
      author: {
        value: '',
        validate: true,
        msg: ''
      },
      email: {
        value: '',
        validate: true,
        msg: ''
      },
      url: {
        value: '',
        validate: true,
        msg: ''
      },
      content: {
        value: '',
        validate: true,
        msg: ''
      },
      imgCode: {
        value: '',
        validate: true,
        msg: ''
      },
      random: {},
      expression: {
        content: {},
        state: true,
        clickState: true
      },
      currentNum: 1,
      bClick: true,
      bMoreList: true,
      bSubmit: true,
      showChart: false,
      sMoreBtnText: '加载中',
      submitText: '提交评论'
    }
  },
  created () {
    if (localStorage.getItem('authorInfo')) {
      const authorInfo = JSON.parse(localStorage.getItem('authorInfo'))
      this.author.value = authorInfo.author
      this.email.value = authorInfo.email
      this.url.value = authorInfo.url
    }

    // 获取评论列表
    API.getCommentList({
      post: this.$route.params.id,
      page: this.currentNum
    }).then(res => {
      this.bMoreList = false
      this.sMoreBtnText = '下一页'
      this.commentList = res.data
      if (this.currentNum === +res.headers['x-wp-totalpages']) {
        this.sMoreBtnText = '最后一页！'
        this.bClick = false
      }
      if (+res.headers['x-wp-totalpages'] === 0) {
        this.sMoreBtnText = '暂无数据！'
        this.bClick = false
      }
    }).catch(err => console.log(err))
  },
  mounted () {
    this.randomCode()

    // 关闭表情显示
    document.body.onclick = () => {
      if (this.$refs.expression) {
        this.expression.clickState = true
        this.$refs.expression.style.display = 'none'
      }
    }
  },
  computed: {
    ...mapState({
      templeteUrl: state => state.info.templeteUrl
    })
  },
  methods: {
    // 评论列表下一页
    getMoreList () {
      this.bMoreList = true
      this.bClick = false
      this.sMoreBtnText = '加载中'
      this.currentNum++
      API.getCommentList({
        post: this.$route.params.id,
        page: this.currentNum
      }).then(res => {
        this.bMoreList = false
        this.bClick = true
        this.sMoreBtnText = '下一页'
        this.commentList = [...this.commentList, ...res.data]
        if (this.currentNum === +res.headers['x-wp-totalpages']) {
          this.sMoreBtnText = '最后一页！'
          this.bClick = false
        }
      }).catch(err => console.log(err))
    },

    // 验证码
    randomCode (bool) {
      let canvas = document.querySelector('.canvas-img-code')
      let ctx = canvas.getContext('2d')
      let nRandom1 = Math.floor(Math.random() * 10 + 5)
      let nRandom2 = Math.floor(Math.random() * 5)
      let nRandomResult = Math.floor(Math.random() * 3)
      let aOperator = ['+', '-', '*']
      ctx.clearRect(0, 0, canvas.width, canvas.height)
      ctx.font = '40px Microsoft Yahei'
      ctx.fillStyle = '#333'
      ctx.textAlign = 'center'
      ctx.fillText(`${nRandom1} ${aOperator[nRandomResult]} ${nRandom2} = ?`, 120, 50)
      this.random = {
        nRandom1,
        nRandom2,
        operator: aOperator[nRandomResult]
      }
    },

    // 验证内容
    contentValidate () {
      if (this.content.value !== '') {
        this.content.validate = false
      } else {
        this.content.validate = true
        this.content.msg = '来点内容吧！'
      }
    },

    // 验证昵称
    authorValidate () {
      if (this.author.value !== '') {
        this.author.validate = false
      } else {
        this.author.validate = true
        this.author.msg = '昵称不能为空！'
      }
    },

    // 验证邮箱
    emailValidate () {
      if (this.email.value !== '') {
        this.email.validate = false
        if (this.email.value.match(/^(\w+|\w+(\.\w+))+@(\w+\.)+\w+$/) === null) {
          this.email.validate = true
          this.email.msg = '邮箱格式错误！'
        }
      } else {
        this.email.validate = true
        if (this.email.value === '') {
          this.email.msg = '邮箱不能为空！'
        }
      }
    },

    // 验证码
    codeValidate () {
      if (this.imgCode.value === '') {
        this.imgCode.validate = true
        this.imgCode.msg = '请输入验证码！'
      } else {
        let _randomCode = this.random
        let result = 0
        switch (_randomCode.operator) {
          case '+':
            result = _randomCode.nRandom1 + _randomCode.nRandom2
            break
          case '-':
            result = _randomCode.nRandom1 - _randomCode.nRandom2
            break
          case '*':
            result = _randomCode.nRandom1 * _randomCode.nRandom2
            break
        }
        if (+this.imgCode.value !== result) {
          this.imgCode.validate = true
          this.imgCode.msg = '是不是看错了？'
        } else {
          this.imgCode.validate = false
        }
      }
    },

    // 提交评论
    postComment () {
      this.contentValidate()
      this.authorValidate()
      this.emailValidate()
      this.codeValidate()
      if (!this.content.validate && !this.author.validate && !this.email.validate && !this.imgCode.validate) {
        this.bSubmit = false
        this.submitText = '提交中...'
        let data = new FormData()
        // 保存评论者信息
        localStorage.setItem('authorInfo', JSON.stringify({
          author: this.author.value,
          email: this.email.value,
          url: this.url.value
        }))
        API.updateComment({
          author_name: this.author.value,
          author_email: this.email.value,
          author_url: this.url.value,
          content: this.content.value.replace(/(\[[a-zA-Z\d]+\])/g, ' $1 '),
          post: this.$route.params.id,
          author_user_agent: navigator.userAgent
        }).then((res) => {
          // 允许继续点击提交按钮
          this.bSubmit = true
          this.submitText = '提交评论'
          this.commentList.unshift(res.data)
          this.content.value = ''
          this.imgCode.value = ''
          this.randomCode()
          this.$message({
            title: '提交评论成功',
            type: 'success'
          })
        }).catch((err) => {
          this.$message({
            title: err.response.data.message,
            type: 'error'
          })
          this.randomCode()
          this.submitText = '提交评论'
          // 允许继续点击提交按钮
          this.bSubmit = true
        })
      }
    },

    // 获取表情
    getExpression () {
      if (this.expression.clickState) {
        this.$refs.expression.style.display = 'block'
        if (this.expression.state) {
          API.getExpression(this.templeteUrl).then(res => {
            this.expression.content = res.data
            this.expression.state = false
          }).catch(err => console.log(err))
        }
      } else {
        this.$refs.expression.style.display = 'none'
      }
      this.expression.clickState = !this.expression.clickState
    },

    // 添加表情
    editExpression (event) {
      this.expression.clickState = true
      this.$refs.expression.style.display = 'none'
      this.content.value += ` ${event.currentTarget.getAttribute('data-title')} `
    },

    // 显示上传图片控件
    showChartlet () {
      this.showChart = true
    },

    // 获取关闭上传控件的值
    getShowChart (params) {
      this.showChart = params.close
    },

    // 获取子组件发回来的图片数据
    getContent (params) {
      this.content.value += params
    }
  }
}
</script>

<style lang="scss" src="../../assets/scss/comment.scss" scoped></style>
