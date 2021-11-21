<template>
  <div class="wrap">
    <h1>{{ blogInfo.blogName }} | 站点地图</h1>
    <div class="breadcrumb-nav">当前位置：<nuxt-link to="/">{{ blogInfo.blogName }}</nuxt-link> > 站点地图</div>

    <section>
      <header>最新文章</header>
      <ul>
        <li v-for="item in articles" :key="item.id">
          <x-icon type="icon-arrow-right-f" />
          <a :href="`${blogInfo.domain}/details/${item.id}`" target="_blank">{{ item.title }}</a>
          <time class="fr create-time">{{ item.createTime }}</time>
        </li>
      </ul>
    </section>

    <section>
      <header>单页面</header>
      <ul>
        <li v-for="item in pages" :key="item.id">
          <x-icon type="icon-arrow-right-f" />
          <a :href="`${blogInfo.domain}/page/${item.id}`" target="_blank">{{ item.title }}</a>
          <time class="fr create-time">{{ item.createTime }}</time>
        </li>
      </ul>
    </section>

    <section>
      <header>文章分类</header>
      <ul>
        <li v-for="item in category" :key="item.id">
          <x-icon type="icon-arrow-right-f" />
          <a :href="`${blogInfo.domain}/category/1?type=${item.id}&title=${item.title}`" target="_blank">{{ item.title }}</a>
        </li>
      </ul>
    </section>

    <section>
      <header>所有标签</header>
      <ul class="list-wrap inline">
        <li v-for="item in tags" :key="item.id" class="tag-item">
          <a :href="`${blogInfo.domain}/tags/1?type=${item.id}&title=${item.title}`" target="_blank">
            {{ item.title }}
            <span>（{{ item.count }}）</span>
          </a>
        </li>
      </ul>
    </section>

    <footer v-html="blogInfo.copyright"></footer>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import XIcon from '../../components/Icon/main'
export default {
  name: 'Sitemap',
  components: { XIcon },
  layout: 'blank',

  async asyncData({ $axios }) {
    const { data } = await $axios.get('/api/wp-json/xm/v2/site/list/all')
    return data
  },

  data() {
    return {
      articles: [],
      category: [],
      pages: [],
      tags: []
    }
  },

  computed: {
    ...mapState({
      blogInfo: state => state.info
    })
  },

  head() {
    return {
      title: `站点地图 | ${this.blogInfo.blogName}`,
      style: [
        { cssText: this.blogInfo.globalCss, type: 'text/css' }
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
.wrap {
  padding-bottom: 30px;
}

h1 {
  margin: 20px 0;
  font-size: 20px;
  text-align: center;
}

section {
  margin-top: 20px;
  padding: 10px;
  border: 1px solid var(--color-border);
  line-height: 2;

  header {
    margin-bottom: 10px;
    font-size: 16px;
  }

  .list-wrap {
    &.inline {
      display: flex;
      flex-wrap: wrap;
    }

    .tag-item {
      margin: 0 8px 8px 0;
      padding: 2px 5px;
      border: 1px solid var(--color-border);
      border-radius: 3px;
    }
  }

  a {
    color: var(--color-sub-text);

    &:hover {
      color: var(--color-theme);
    }
  }
}

footer {
  margin-top: 30px;
  text-align: center;
}

@media screen and (max-width:750px) {
  .create-time {
    display: none;
  }
}
</style>
