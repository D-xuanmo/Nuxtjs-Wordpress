<template>
  <div class="comment-list-wrapper" :class="isChild && 'is-child'">
    <ul class="comment-list">
      <li v-for="item in list" :key="item.key" class="comment-list-item">
        <!-- 头像 -->
        <div class="comment-list-item__avatar">
          <span
            v-if="item.isTextAvatar"
            class="comment-list-item--text-avatar"
            :style="{ background: item.avatarColor }"
          >{{ item.ahutorName.substr(0, 1) }}</span>
          <img v-else src="" alt="">
        </div>

        <div class="comment-list-item__content">
          <header class="comment-list-item__content-header">
            <div>
              <a :href="item.authorSite" class="comment-list-item--author" target="_blank">{{ item.ahutorName }}</a>
              <span
                class="icon-vip icon-level comment-list-item--level"
                :class="[item.authorLevel.style, item.authorLevel.level]"
                :title="item.authorLevel.title">
              </span>
              <img
                v-if="item.authorLevel.isAdmin"
                src="../../assets/images/icon-admin.png"
                alt="博主"
                title="博主"
                width="20"
                style="vertical-align: baseline"
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
            <div v-html="item.content" />
            <p v-if="!item.isApproved" class="comment-list-item--approved">您的评论正在等待审核</p>
            <div class="comment-list-item--footer">
              <p style="display: flex">
                <span class="comment-list-item--app-icon">
                  <svg-icon :iconName="APP_ICONS[item.userAgent.os]" />
                  <span>{{ item.userAgent.os }} {{ item.userAgent.osVersion }}</span>
                </span>
                <span class="comment-list-item--app-icon">
                  <svg-icon :iconName="APP_ICONS[item.userAgent.browser]" />
                  <span>{{ item.userAgent.browserZH }} {{ item.userAgent.browserVersion }}</span>
                </span>
              </p>
              <p></p>
            </div>
          </div>

          <comment-form
            :id="item.id"
            :post-id="$route.params.id"
            :parent-id="isChild ? item.parentId : item.id"
            is-child
          />
          <comment-list v-if="item.children" :list="item.children" is-child />
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapMutations } from 'vuex'
import CommentForm from './CommentForm'
import { APP_ICONS } from '../../constants'

export default {
  name: 'CommentList',

  components: {
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
    }
  },

  data() {
    return {
      APP_ICONS
    }
  },

  methods: {
    ...mapMutations('comment', ['SET_COMMENT_CURRENT_FORM_ID'])
  }
}
</script>

<style lang="scss" scoped>
.comment-list-wrapper {
  margin-top: var(--base-gap);
}
.comment-list {
  &-item {
    display: flex;
    width: 100%;

    &--level.vip-style-1 {
      display: inline-block;
      width: 20px;
      height: 18px;
      @for $i from 1 through 7 {
        &.vip#{$i} {
          background-repeat: no-repeat;
          background-image: url("../../assets/images/vip-style-1-#{$i}.png");
          background-size: contain;
        }
      }
    }

    & + .comment-list-item {
      margin-top: var(--large-gap);
    }

    .comment-list-item {
      margin-top: var(--small-gap);
    }

    &__content {
      flex: 1;
    }

    &--text-avatar {
      display: inline-block;
      width: 50px;
      height: 50px;
      margin-right: var(--base-gap);
      border-radius: 5px;
      font-size: 22px;
      text-align: center;
      line-height: 50px;
      color: #fff;
    }

    &--reply {
      font-size: 12px;
      color: $color-sub-text;

      &-btn {
        margin-left: var(--small-gap);
        cursor: pointer;
      }
    }

    &--author {
      font-size: 18px;
    }

    &--content {
      margin-top: var(--small-gap);
      padding: var(--base-gap) var(--small-gap);
      border-radius: $border-radius;
      background: var(--color-main-background);
      white-space: pre-line;
    }

    &--approved {
      margin-top: var(--small-gap);
      font-size: 12px;
    }

    &--footer {
      margin-top: var(--base-gap);
    }

    &--app-icon {
      display: flex;
      align-items: center;

      & + .comment-list-item--app-icon {
        margin-left: var(--base-gap);
      }

      svg {
        width: 15px;
        height: 15px;
        margin-right: var(--small-gap);
        vertical-align: middle;
      }
    }
  }
}
</style>
