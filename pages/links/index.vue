<template>
  <page-detail
    :title="detail.title"
    :content="detail.content"
    :comment-count="detail.commentCount || 0"
    :comment-status="detail.comment_status"
    :page-id="detail.id"
  >
    <article class="article link-wrapper">
      <h2 class="link-list--section-title">友情链接</h2>
      <ul class="link-list">
        <li v-for="item in innerList" :key="item.id" class="link-list__item">
          <a :href="item.url" :target="item.target" class="link-list__item-link">
            <img :src="item.logo" class="link-list__item--logo" :alt="item.name">
            <div class="link-list__item-content">
              <p class="link-list__item--title" :title="item.name">{{ item.name }}</p>
              <p class="link-list__item--describe" :title="item.description">{{ item.description }}</p>
            </div>
          </a>
        </li>
      </ul>
    </article>
    <article class="article link-wrapper">
      <h2 class="link-list--section-title">首页链接</h2>
      <ul class="link-list">
        <li v-for="item in links" :key="item.id" class="link-list__item">
          <a :href="item.url" :target="item.target" class="link-list__item-link">
            <img :src="item.logo" class="link-list__item--logo" :alt="item.name">
            <div class="link-list__item-content">
              <p class="link-list__item--title" :title="item.name">{{ item.name }}</p>
              <p class="link-list__item--describe" :title="item.description">{{ item.description }}</p>
            </div>
          </a>
        </li>
      </ul>
    </article>
  </page-detail>
</template>

<script>
import { mapState } from 'vuex'
import PageDetail from '~/components/PageDetail'

export default {
  name: 'LinkDetail',

  layout: 'page',

  fetch({ store }) {
    return store.dispatch('link-detail/getLinkPageDetail')
  },

  components: {
    PageDetail
  },

  computed: {
    ...mapState('link-detail', ['detail', 'innerList']),
    ...mapState(['links'])
  }
}
</script>

<style lang="scss" scoped>
.link {
  &-wrapper {
    margin-top: 24px;
  }

  &-list {
    display: flex;
    flex-wrap: wrap;

    &--section-title {
      font-size: 16px;
    }

    &__item {
      width: calc(25% - var(--base-gap) + var(--base-gap) / 4);
      margin: var(--base-gap) var(--base-gap) 0 0;
      padding: 10px;
      background: var(--color-main-background);
      box-sizing: border-box;

      &:nth-of-type(4n) {
        margin-right: 0;
      }

      &-link {
        display: flex;
        align-items: center;
      }

      &-content {
        flex: 1;
        overflow: hidden;
      }

      &--title,
      &--describe {
        @extend %ellipsis;
      }

      &--describe {
        color: var(--color-secondary);
      }

      &--logo {
        width: 40px;
        height: 40px;
        margin-right: var(--small-gap);
      }
    }
  }
}

@media screen and (max-width:767px) {
  .link {
    &-list {
      &__item {
        width: 100%;
        margin: var(--base-gap) 0 0 0;
      }
    }
  }
}
</style>
