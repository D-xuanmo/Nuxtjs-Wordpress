<template>
  <div v-if="commentFormId === id" class="comment-form-wrapper" :class="isChild && 'is-child'">
    <header class="comment-form-header">
      <template v-if="!isChild">
        <h3 class="comment-title">发表评论</h3>
        <p class="comment-form-tips">昵称、邮箱为必填项，电子邮件地址不会被公开</p>
      </template>
      <div class="p-relative">
        <button v-if="info.isOpenCommentUpload" class="comment-form-icon-btn" @click="isShowUpload = true">
          <x-icon type="icon-upload-img2" />
          <span class="vertical-middle">图片</span>
        </button>
        <button class="comment-form-icon-btn" @click.stop="isShowExpression = true">
          <x-icon type="icon-expression" />
          <span class="vertical-middle">表情</span>
        </button>
        <comment-upload v-if="isShowUpload" @on-confirm="onUploadImage" @on-close="isShowUpload = false" />
        <comment-expression
          :visible.sync="isShowExpression"
          @on-change="onExpressionChange"
          @on-close="isShowExpression = false"
        />
      </div>
    </header>

    <div class="comment-form-content">
      <textarea
        v-model="content"
        class="comment-input--inner"
        name="content"
        rows="8"
        placeholder="留下你的脚印吧~"
      />
    </div>

    <div class="comment-form-input">
      <div v-for="item in form" :key="item.key" class="comment-form-input-wrapper">
        <div class="comment-form-input-row" :data-error="item.errorMsg">
          <label :for="item.fieldName" :class="item.errorMsg && 'comment-form-input--error'">{{ item.label }}</label>
          <input
            v-model="item.value"
            :id="item.fieldName"
            type="text"
            class="comment-form-input--inner"
            :name="item.fieldName"
            :placeholder="item.placeholder"
            autocomplete="off"
            @input="onInput($event, item)"
          >
        </div>
      </div>
      <button v-if="isChild" class="comment-form-btn is-close" @click="SET_COMMENT_CURRENT_FORM_ID('0')">取消</button>
      <button class="comment-form-btn is-submit" :class="isChild && 'is-child'" @click="submit">
        <x-icon :type="loading ? 'icon-loading' : 'icon-send'" />
        提交评论
      </button>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import { createRandomID, debounce } from '@xuanmo/javascript-utils'
import CommentUpload from './CommentUpload'
import CommentExpression from './CommentExpression'

export default {
  name: 'CommentForm',

  components: {
    CommentUpload,
    CommentExpression
  },

  props: {
    id: {
      type: String,
      default: '0'
    },

    postId: {
      required: true,
      type: [Number, String],
      default: ''
    },

    parentId: {
      type: String,
      default: '0'
    },

    isChild: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    ...mapState(['info']),
    ...mapState('comment', ['commentFormId'])
  },

  data() {
    const cacheAuthorInfo = JSON.parse(window.localStorage.getItem('authorInfo') || '{}')
    const fields = {
      name: ['昵称', '必填'],
      email: ['邮箱', '必填'],
      url: ['网址', '选填']
      // code: ['验证码', '必填']
    }
    const form = Object.keys(fields).map(item => ({
      index: createRandomID(),
      fieldName: item,
      label: fields[item][0],
      placeholder: fields[item][1],
      value: cacheAuthorInfo[item],
      errorMsg: ''
    }))
    return {
      isShowUpload: false,
      isShowExpression: false,
      form,
      content: '',
      loading: false,
      validate: {
        name: [
          { required: true, message: '昵称不能为空' }
        ],
        email: [
          { required: true, message: '邮箱不能为空' },
          { pattern: /^(\w+|\w+(\.\w+))+@(\w+\.)+\w+$/, message: '邮箱格式不对' }
        ],
        code: [
          { require: true, message: '验证码不能为空' }
        ]
      }
    }
  },

  created() {
    this.initValidate()
  },

  methods: {
    ...mapMutations('comment', ['SET_COMMENT_CURRENT_FORM_ID']),
    ...mapActions('comment', ['updateComment']),

    initValidate() {
      this.onInputDebounce = debounce((value, data) => {
        this.handleValidator(value, data)
      }, 300)
    },

    /**
     * 执行校验
     * @param value
     * @param data
     * @returns {string|*}
     */
    handleValidator(value, data) {
      const { fieldName } = data
      const currentValidate = this.validate[fieldName]
      if (currentValidate) {
        for (let i = 0; i < currentValidate.length; i++) {
          const item = currentValidate[i]
          if (item.required && !value) {
            data.errorMsg = item.message
            break
          } else {
            const _item = currentValidate[i + 1]
            if (_item && !_item?.pattern?.test(value)) {
              data.errorMsg = _item.message
              break
            } else {
              data.errorMsg = ''
            }
          }
        }
        return data.errorMsg
      }
    },

    /**
     * 输入框输入事件
     * @param event
     * @param data
     */
    onInput(event, data) {
      this.onInputDebounce(event.target.value, data)
    },

    /**
     * 获取上传文件结果
     * @param content
     */
    onUploadImage(content) {
      this.content += content
    },

    onExpressionChange(value) {
      this.content += value
    },

    async submit() {
      // 内容为空
      if (!this.content) {
        return this.$message({
          type: 'warning',
          title: '内容不能为空'
        })
      }

      const formData = {
        content: this.content,
        author_user_agent: navigator.userAgent,
        post: this.postId,
        parent: this.parentId
      }

      // 判断每个字段校验是否通过
      const formList = this.form
      for (let i = 0; i < formList.length; i++) {
        const item = formList[i]
        const errorMsg = this.handleValidator(item.value, item)
        if (errorMsg) {
          return this.$message({
            type: 'warning',
            title: errorMsg
          })
        }
        if (item.fieldName !== 'code') formData[`author_${item.fieldName}`] = item.value
      }

      // 缓存用户信息
      localStorage.setItem('authorInfo', JSON.stringify({
        name: formData.author_name,
        email: formData.author_email,
        url: formData.author_url
      }))

      try {
        this.loading = true
        await this.updateComment(formData)
        this.loading = false
        this.$message({
          type: 'success',
          title: '提交成功'
        })
        this.content = ''
      } catch (error) {
        this.loading = false
        this.$message({
          type: 'error',
          title: `提交失败：${error.data.message}`
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.comment {
  &-form {
    &-wrapper {
      margin-bottom: var(--base-gap);
      padding: var(--base-gap);
      background: var(--color-main-background);

      &.is-child {
        padding-top: 0;
      }
    }

    &-tips {
      margin-bottom: var(--small-gap);
      font-size: 12px;
      color: var(--color-secondary);
    }

    &-icon-btn {
      font-size: 12px;
      cursor: pointer;

      &:first-of-type {
        margin: 0 var(--base-gap) 0 0;
      }

      .iconfont {
        font-size: 16px;
        vertical-align: middle;
      }
    }

    &-content {
      margin-bottom: var(--base-gap);

      .comment-input--inner {
        box-sizing: border-box;
        width: 100%;
        margin: var(--small-gap) 0 0 0;
        padding: var(--small-gap);
        vertical-align: top;
        background: var(--color-sub-background);
        border-radius: $border-radius;
        resize: vertical;
      }
    }

    &-input {
      display: flex;
      margin-bottom: var(--small-gap);

      &-wrapper {
        width: calc((100% - 250px) / 3 - var(--large-gap));

        & + .comment-form-input-wrapper {
          margin-left: var(--base-gap);
        }

        label {
          word-break: keep-all;
        }
      }

      &-row {
        position: relative;
        display: flex;
        padding: var(--small-gap);
        background: var(--color-sub-background);
        border-radius: $border-radius;

        &:after {
          content: attr(data-error);
          position: absolute;
          bottom: 0;
          left: var(--small-gap);
          font-size: 12px;
          color: #f5222d;
          transform: translateY(calc(100% + 3px));
        }
      }

      &--inner {
        width: 100%;
        margin-left: var(--small-gap);
      }
    }

    &-btn {
      margin-left: auto;
      padding: var(--small-gap) var(--base-gap);
      border-radius: $border-radius;
      font-family: inherit;
      word-break: keep-all;
      white-space: nowrap;
      color: var(--color-theme);
      cursor: pointer;

      &.is-close {
        background: var(--color-sub-background);
      }

      &.is-submit {
        background: var(--color-theme-secondary);

        &.is-child {
          margin-left: var(--base-gap);
        }
      }
    }
  }
}

@media screen and (max-width: 1024px) {
  .comment {
    &-form {
      &-input {
        flex-direction: column;

        &-wrapper {
          width: 100%;

          & + .comment-form-input-wrapper {
            margin: var(--base-gap) 0 0 0;
          }
        }
      }

      &-btn {
        width: 100%;
        margin: var(--base-gap) 0 0 0;

        &.is-submit.is-child {
          margin-left: 0;
        }
      }
    }
  }
}
</style>
