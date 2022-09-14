<template>
  <div
    class="comment-list-wrapper"
    :class="isChild && 'is-child'"
  >
    <ul class="comment-list">
      <li
        v-for="item in list"
        :key="item.key"
        class="comment-list-item"
        :class="{
          'is-child': isChild,
          'has-child': item.hasChildren
        }"
        :data-level="item._level"
        :style="{
          width: item._level.length > 2
            ? `calc(100% + calc(var(--avatar-width) + var(--small-gap)) * ${item._level.length - parentLevel.length})`
            : undefined
        }"
      >
        <!-- 头像 -->
        <div class="comment-list-item__avatar">
          <span
            v-if="item.isTextAvatar"
            class="comment-list-item--text-avatar"
            :style="{ background: item.avatarColor }"
          >{{ item.authorName.substr(0, 1) }}</span>
          <img v-else :src="item.avatar" class="comment-list-item--image-avatar" />
        </div>

        <div class="comment-list-item__content">
          <header class="comment-list-item__content-header">
            <div style="display: flex; align-items: center">
              <comment-list-item-author
                :author="{
                  name: item.authorName,
                  site: item.authorSite,
                  style: item.authorLevel.style,
                  level: item.authorLevel.level,
                  levelTitle: item.authorLevel.title,
                  isAdmin: item.authorLevel.isAdmin
                }"
              />
            </div>

            <div class="comment-list-item--reply">
              <time>{{ item.createTime }}</time>
              <a class="comment-list-item--reply-btn" @click="() => SET_COMMENT_CURRENT_FORM_ID(item.id)">
                <x-icon type="icon-send" /> 回复
              </a>
            </div>
          </header>

          <div class="comment-list-item--content">
            <!-- 当前评论内容 -->
            <div v-html="item.content" />

            <!-- 父级评论内容 -->
            <div
              v-if="item.parent.authorName && item._level.length > 2"
              class="comment-list-item--parent-content"
              :title="item.parent.content"
              v-html="`@${item.parent.authorName} - ${item.parent.content}`"
            />

            <p v-if="!item.isApproved" class="comment-list-item--approved">您的评论正在等待审核</p>
            <div class="comment-list-item--footer">
              <p class="comment-list-item__app">
                <span class="comment-list-item--app-icon">
                  <svg-icon :iconName="APP_ICONS[formatUA(item.ua, 'os')]" />
                  <span class="desktop-show">{{ formatUA(item.ua, 'os') }}&nbsp;</span>
                  <span>{{ formatUA(item.ua, 'osVersion') }}</span>
                </span>
                <span class="comment-list-item--app-icon">
                  <svg-icon :iconName="APP_ICONS[formatUA(item.ua, 'browser')]" />
                  <span class="desktop-show vertical-middle">{{ formatUA(item.ua, 'browserZH') }}&nbsp;</span>
                  <span class="vertical-middle">{{ formatUA(item.ua, 'browserVersion').replace(/(\d+\.\d+).*/, '$1') }}</span>
                </span>
              </p>
              <p class="comment-list-item--opinion">
                <span @click="() => _updateCommentOpinion(item, 'good')">
                  <x-icon type="icon-good" /> {{ item.opinion.good || 0 }}
                </span>
              </p>
            </div>
          </div>

          <comment-form
            :id="item.id"
            :post-id="postId"
            :parent-id="item.id"
            is-child
          />
          <comment-list
            v-if="item.children.length"
            :list="item.children"
            :page-id="postId"
            :parent-level="item._level"
            is-child
          />

          <div v-if="!isChild && item.hasChildren" class="comment-list-item--more-btn">
            <span v-if="item.loading"><x-icon type="icon-loading" /> 加载中...</span>
            <span v-else @click="_getSingleComment(item.id)">
              查看全部 {{ item.childrenCount }} 条回复 <x-icon type="icon-arrow-bottom" />
            </span>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapActions, mapMutations } from 'vuex'
import CommentForm from './CommentForm'
import { APP_ICONS } from '../../constants'
import XIcon from '../Icon/main'
import { ua } from '@xuanmo/javascript-utils'
import CommentListItemAuthor from './CommentListItemAuthor'

export default {
  name: 'CommentList',

  components: {
    CommentListItemAuthor,
    XIcon,
    CommentForm
  },

  props: {
    list: {
      required: true,
      type: Array,
      default: () => []
    },

    isChild: {
      type: Boolean,
      default: false
    },

    parentLevel: {
      type: String,
      default: ''
    },

    pageId: [String, Number]
  },

  computed: {
    postId() {
      return this.pageId || this.$route.params.id
    }
  },

  data() {
    return {
      APP_ICONS
    }
  },

  methods: {
    ...mapMutations('comment', ['SET_COMMENT_CURRENT_FORM_ID']),
    ...mapActions('comment', ['updateCommentOpinion', 'getSingleComment']),

    formatUA(data, key) {
      return ua(data)[key].toString() || ''
    },

    _getSingleComment(commentId) {
      this.getSingleComment({
        postId: this.$route.params.id,
        commentId
      });
    },

    /**
     * 评论点赞、踩
     * @param item
     * @param type
     * @returns {Promise<void>}
     */
    async _updateCommentOpinion (item, type) {
      const { id, parentId } = item
      if (localStorage.getItem(`commentOpinion_${id}`)) {
        this.$message({ title: '已经发表过了哦！', type: 'warning' })
        return
      }
      try {
        await this.updateCommentOpinion({ id, parentId, type })
        this.$message({ title: '提交成功', type: 'success' })
        localStorage.setItem(`commentOpinion_${id}`, true)
      } catch (error) {
        this.$message({ title: '哦豁，失败了！', type: 'error' })
      }
    }
  }
}
</script>

<style lang="scss">
.comment-list-wrapper {
  --avatar-width: 30px;
  margin-top: var(--base-gap);

  &.is-child {
    .comment-list-wrapper.is-child {
      transform: translateX(#{calc(-1 * calc(var(--avatar-width) + var(--small-gap)))});
    }
  }
}

.comment-list {
  &-item {
    display: flex;
    width: 100%;

    & + .comment-list-item {
      margin-top: var(--large-gap);
    }

    .comment-list-item {
      margin-top: var(--small-gap);
    }

    &__content {
      flex: 1;
      width: 0;
    }

    &--more-btn {
      margin-top: var(--small-gap);
      padding-left: calc(var(--avatar-width) + var(--small-gap));
      color: var(--color-secondary);
      cursor: pointer;

      &.is-loading {
        cursor: wait;
      }
    }

    &__avatar {
      margin-right: var(--small-gap);
    }

    &--image-avatar {
      width: var(--avatar-width);
      height: var(--avatar-width);
      border-radius: 50%;
    }

    &--text-avatar {
      display: inline-block;
      width: var(--avatar-width);
      height: var(--avatar-width);
      border-radius: 5px;
      font-size: 22px;
      text-align: center;
      line-height: var(--avatar-width);
      color: #fff;
    }

    &--reply {
      font-size: 12px;
      color: var(--color-secondary);

      &-btn {
        margin-left: var(--small-gap);
        color: var(--color-secondary);
        cursor: pointer;
      }
    }

    &__author {
      &-name {
        font-size: 18px;
      }

      &-level.vip-style-1 {
        display: inline-block;
        width: 18px;
      }
    }

    &--content {
      margin-top: var(--small-gap);
      padding: var(--base-gap);
      border-radius: $border-radius;
      background: var(--color-main-background);
      white-space: pre-line;

      a {
        color: var(--color-theme);
      }
    }

    &--parent-content {
      margin-top: var(--base-gap);
      padding: var(--small-gap);
      border-radius: 3px;
      background: var(--color-sub-background);
      @extend %ellipsis;

      img {
        display: none;
      }
    }

    &--approved {
      margin-top: var(--small-gap);
      font-size: 12px;
    }

    &--footer {
      display: flex;
      justify-content: space-between;
      margin-top: var(--base-gap);
      padding-top: var(--small-gap);
      border-top: 1px dashed var(--color-border);
      font-size: 12px;
    }

    &--opinion {
      display: flex;
      color: var(--color-secondary);
      span {
        cursor: pointer;

        & + span {
          margin-left: var(--base-gap);
        }
      }
    }

    &__app {
      display: flex;
    }

    &--app-icon {
      display: flex;
      align-items: center;
      color: var(--color-secondary);

      & + .comment-list-item--app-icon {
        margin-left: var(--base-gap);
      }

      svg {
        width: 12px !important;
        height: 12px !important;
        margin-right: 4px;
        vertical-align: middle;
      }
    }
  }
}

@media screen and (max-width: 768px) {
  .comment-list-wrapper {
    margin-top: var(--base-gap);

    .comment-list-item {
      &__avatar {
        padding-top: 5px;
      }

      &--app-icon svg {
        width: 15px;
        height: 15px;
      }
    }
  }
}

@media screen and (max-width: 320px) {
  .comment-list-wrapper {
    margin-top: var(--base-gap);

    .comment-list-item {
      &__avatar {
        padding-top: 5px;
      }

      &__app {
        display: none;
      }
    }
  }
}
</style>
