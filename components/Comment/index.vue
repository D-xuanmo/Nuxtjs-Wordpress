<template>
  <div class="comments-wrap">
    <!-- 发表评论 -->
    <div v-if="commentStatus === 'closed'" class="comment-closed text-center f-s-large">评论已关闭</div>
    <div v-else-if="commentStatus === 'open'" class="comment-from">
      <h3 class="comment-title">发表评论</h3>
      <p class="comment-sub-title">电子邮件地址不会被公开。 必填项已用<i class="c-red">*</i>标注</p>
      <!-- 评论其他功能 -->
      <div class="comment-other">
        <ul class="list-wrap">
          <li v-if="isOpenCommentUpload" class="list" @click="_showChartlet">
            <x-icon type="icon-upload-img2"></x-icon>贴图
          </li>
          <li class="list" @click.stop="_getExpression">
            <x-icon type="icon-expression"></x-icon>表情
          </li>
        </ul>
        <upload v-show="showChart" @showChart="_getShowChart" @updateContent="_getContent" :showChart="showChart"></upload>
        <!-- 表情 -->
        <expression :is-show="expression.isShow" @on-change="_insertExpression" />
      </div>
      <!-- 评论输入框 -->
      <div class="comment-form-content">
        <label for="content">内容<i class="c-red">*</i></label>
        <textarea id="content" name="content" v-model="content.value" rows="8" cols="80" @keyup="_contentValidate"></textarea>
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
            @keyup="_authorValidate"
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
            @keyup="_emailValidate"
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
            @keyup="_codeValidate"
          >
          <canvas width="240" height="60" class="canvas-img-code" @click="_randomCode"></canvas>
          <span v-show="imgCode.validate" class="comment-tips">{{ imgCode.msg }}</span>
        </div>
      </div>
      <!-- 提交按钮 -->
      <div class="submit-wrap" @click.prevent="bSubmit && _postComment()">
        <i v-show="!bSubmit" class="iconfont icon-loading"></i>
        <input type="submit" class="submit-btn" :value="submitText">
      </div>
    </div>
    <!-- 评论列表 -->
    <ul class="comment-list-wrap">
      <li class="comment-list" v-for="(item, index) in commentList" :key="item.key">
        <template>
          <p
            v-if="info.isOpenTextThumbnail"
            class="list-gravatar-text"
            :style="{ background: item.userAgentInfo.background }">
            {{ item.author_name.substr(0, 1) }}
          </p>
          <img
            v-else
            :src="item.userAgentInfo.author_avatar_urls"
            class="list-gravatar"
            width="60"
            height="60"
            alt="">
        </template>
        <div class="list-header">
          <a :name="`comment-${item.id}`"></a>
          <a :href="item.author_url" target="_blank" class="author">{{ item.author_name }}</a>
          <!-- 评论者等级 -->
          <span
            class="icon-vip icon-level"
            :class="[item.userAgentInfo.vipStyle.style, item.userAgentInfo.vipStyle.level]"
            :title="item.userAgentInfo.vipStyle.title">
          </span>
          <img v-if="item.userAgentInfo.vipStyle.title === '博主'" src="../../assets/images/icon-admin.png" width="20" class="icon-admin">
          <p class="inline-block system-wrap">
            <!-- 浏览器logo -->
            <span :class="['browser-info', item.userAgent.browserName.toLowerCase()]">
              {{ item.userAgent.browserName }} {{ item.userAgent.browserVersion }}
            </span>
            <!-- 系统logo -->
            <span class="system-info">{{ item.userAgent.systemName }} {{ item.userAgent.systemVersion }}</span>
          </p>
          <span v-if="item.status === 'hold'">您的评论正在审核中...</span>
        </div>
        <div class="list-content" v-html="item.content.rendered"></div>
        <div class="list-footer">
          <time>{{ item.date.replace('T', ' ') }}</time>
          <div class="opinion">
            <span class="opinion-btn" @click="_updateCommentOpinion(item.id, 'good', index)">
              <x-icon type="icon-good"></x-icon> {{ item.meta.opinion.good }}
            </span>
            <span class="opinion-btn" @click="_updateCommentOpinion(item.id, 'bad', index)">
              <x-icon type="icon-bad"></x-icon> {{ item.meta.opinion.bad }}
            </span>
          </div>
        </div>
        <!-- <div class="list-btn-wrap">
          <a href="#">回复</a>
        </div> -->
      </li>
    </ul>
    <div class="more-btn" @click="bClick && _getMoreList()">
      <span><i v-show="bMoreList" class="iconfont icon-loading"></i>{{ sMoreBtnText }}</span>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import Upload from './upload'
import Expression from './expression'
export default {
  name: 'Comments',
  components: {
    Upload,
    Expression
  },
  props: {
    commentStatus: {
      type: String,
      default: 'open'
    }
  },
  data () {
    return {
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
        isFirstRequest: true,
        isShow: false,
        clickState: true
      },
      currentCommentPage: 1,
      bClick: true,
      bMoreList: true,
      bSubmit: true,
      showChart: false,
      sMoreBtnText: '评论加载中',
      submitText: '提交评论'
    }
  },
  computed: {
    ...mapState(['info']),
    ...mapState({
      isOpenCommentUpload: state => state.info.isOpenCommentUpload,
      templeteUrl: state => state.info.templeteUrl
    }),
    ...mapState('comment', ['commentList', 'totalPage'])
  },
  created () {
    this.init()
  },
  beforeDestroy () {
    this.$store.commit('comment/RESET_COMMENT')
  },
  methods: {
    ...mapActions('comment', ['getCommentList', 'updateComment', 'getExpression', 'updateCommentOpinion']),

    async init () {
      if (localStorage.getItem('authorInfo')) {
        const authorInfo = JSON.parse(localStorage.getItem('authorInfo'))
        this.author.value = authorInfo.author
        this.email.value = authorInfo.email
        this.url.value = authorInfo.url
      }

      // 获取评论列表
      await this.getCommentList({
        post: this.$route.params.id,
        page: this.currentCommentPage
      })
      this.bMoreList = false
      this.sMoreBtnText = '下一页'
      if (this.currentCommentPage === this.totalPage) {
        this.sMoreBtnText = '最后一页！'
        this.bClick = false
      }
      if (this.totalPage === 0) {
        this.sMoreBtnText = '暂无数据！'
        this.bClick = false
      }
      this.commentStatus === 'open' && this.$nextTick(() => this._randomCode())
    },

    // 评论列表下一页
    async _getMoreList () {
      this.bMoreList = true
      this.bClick = false
      this.currentCommentPage++
      this.sMoreBtnText = '加载中'
      await this.getCommentList({
        post: this.$route.params.id,
        page: this.currentCommentPage
      })
      this.bMoreList = false
      this.bClick = true
      this.sMoreBtnText = '下一页'
      if (this.currentCommentPage === this.totalPage) {
        this.sMoreBtnText = '最后一页！'
        this.bClick = false
        return
      }
      this.bMoreList = false
      this.bClick = true
      this.sMoreBtnText = '下一页'
    },

    // 验证码
    _randomCode () {
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
    _contentValidate () {
      if (this.content.value !== '') {
        this.content.validate = false
      } else {
        this.content.validate = true
        this.content.msg = '来点内容吧！'
      }
    },

    // 验证昵称
    _authorValidate () {
      if (this.author.value !== '') {
        this.author.validate = false
      } else {
        this.author.validate = true
        this.author.msg = '昵称不能为空！'
      }
    },

    // 验证邮箱
    _emailValidate () {
      if (this.email.value !== '') {
        this.email.validate = false
        if (!/^(\w+|\w+(\.\w+))+@(\w+\.)+\w+$/.test(this.email.value)) {
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
    _codeValidate () {
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
    async _postComment () {
      this._contentValidate()
      this._authorValidate()
      this._emailValidate()
      this._codeValidate()
      if (!this.content.validate && !this.author.validate && !this.email.validate && !this.imgCode.validate) {
        this.bSubmit = false
        this.submitText = '提交中...'
        // 保存评论者信息
        localStorage.setItem('authorInfo', JSON.stringify({
          author: this.author.value,
          email: this.email.value,
          url: this.url.value
        }))
        try {
          let data = await this.updateComment({
            author_name: this.author.value,
            author_email: this.email.value,
            author_url: this.url.value,
            content: this.content.value.replace(/(\[[a-zA-Z\d]+\])/g, ' $1 '),
            post: this.$route.params.id,
            author_user_agent: navigator.userAgent
          })
          // 允许继续点击提交按钮
          this.bSubmit = true
          this.submitText = '提交评论'
          this.$store.commit('comment/UPDATE_COMMENT', data)
          this.content.value = ''
          this.imgCode.value = ''
          this._randomCode()
          this.$message({
            title: '提交评论成功',
            type: 'success'
          })
        } catch (error) {
          this.$message({
            title: error,
            type: 'error'
          })
          this._randomCode()
          this.submitText = '提交评论'
          this.bSubmit = true
        }
      }
    },

    // 获取表情
    async _getExpression () {
      if (this.expression.clickState) {
        this.expression.isShow = true
        if (this.expression.isFirstRequest) {
          await this.getExpression()
          this.expression.isFirstRequest = false
        }
      } else {
        this.expression.isShow = false
      }
      this.expression.clickState = !this.expression.clickState
    },

    // 插入表情
    _insertExpression (data) {
      if (data.type === 'insert') this.content.value += ` ${data.value} `
      this.expression.clickState = true
      this.expression.isShow = false
    },

    // 显示上传图片控件
    _showChartlet () {
      this.showChart = true
    },

    // 获取关闭上传控件的值
    _getShowChart (params) {
      this.showChart = params.close
    },

    // 获取子组件发回来的图片数据
    _getContent (params) {
      this.content.value += params
    },

    // 评论点赞、踩
    async _updateCommentOpinion (id, type, index) {
      if (localStorage.getItem(`commentOpinion_${id}`)) {
        this.$message({ title: '已经发表过了哦！', type: 'warning' })
        return
      }
      try {
        let data = await this.updateCommentOpinion({
          id,
          type
        })
        this.$store.commit('comment/UPDATE_COMMENT_OPINION', { index, data })
        localStorage.setItem(`commentOpinion_${id}`, true)
      } catch (error) {
        this.$message({ title: '哦豁，失败了！', type: 'error' })
      }
    }
  }
}
</script>

<style lang="scss" src="../../assets/scss/comment.scss" scoped></style>
