<template>
  <div class="container article-list-wrap m-t-0px">
    <ul class="header">
      <li class="list">当前标签：{{ $route.query.title }}</li>
    </ul>
    <article class="article-list" v-for="item in articleList" :key="item.key">
      <nuxt-link v-if="item.articleInfor.thumbnail" :to="{ name: 'details-id', params: { id: item.id } }" class="thumbnail-wrap">
        <img :src="item.articleInfor.thumbnail" class="thumbnail" alt="">
      </nuxt-link>
      <div class="list-content">
        <h2 class="title" :class="!item.articleInfor.thumbnail && 'no-thumbnail'">
          <span class="classify" v-html="item.articleInfor.classify[0].name"></span>
          <nuxt-link :to="{ name: 'details-id', params: { id: item.id } }" class="vertical-middle" v-html="item.title.rendered"></nuxt-link>
        </h2>
        <p class="summary">{{ item.articleInfor.summary }}</p>
        <div class="opeartion">
          <div class="information">
            <span><x-icon type="icon-date"></x-icon>{{ item.date }}</span>
            <span><x-icon type="icon-hot1"></x-icon>{{ item.articleInfor.viewCount }}</span>
            <span><x-icon type="icon-message"></x-icon>{{ item.articleInfor.commentCount }}</span>
            <span><x-icon type="icon-good"></x-icon>{{ item.articleInfor.xmLike.very_good }}</span>
          </div>
          <nuxt-link class="details-btn" :to="{ name: 'details-id', params: { id: item.id } }">阅读详情</nuxt-link>
        </div>
      </div>
    </article>
    <!-- more btn start -->
    <el-pagination
      small
      :page-size="8"
      layout="prev, pager, next, jumper"
      :current-page="currentPage"
      @current-change="_changePagination"
      :total="totalPage">
    </el-pagination>
    <!-- more btn end -->
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'Tags',
  watchQuery: ['type'],
  fetch ({ params, query, store }) {
    store.commit('article/SET_CURRENT_PAGE', +params.id)
    return store.dispatch('article/getArticleList', {
      tags: query.type,
      page: params.id,
      per_page: 8,
      _embed: true
    })
  },
  computed: {
    ...mapState(['globalConfig']),
    ...mapState('article', ['articleList', 'totalPage', 'currentPage'])
  },
  head () {
    return {
      title: this.$route.query.title
    }
  },
  methods: {
    _changePagination (id) {
      this.$store.commit('article/SET_CURRENT_PAGE', id)
      this.$router.push({
        name: 'tags-id',
        params: { id },
        query: {
          type: this.$route.query.type,
          title: this.$route.query.title
        }
      })
    }
  }
}
</script>
