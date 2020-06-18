import {
  UPDATE_ARTICLE_LIST,
  SET_ARTICLE_DETAIL,
  SET_TOTAL_PAGE,
  SET_CURRENT_PAGE,
  UPDATE_VIEW_COUNT,
  UPDATE_OPINION,
  UPDATE_OPINION_LOADING
} from './mutations-types'

export const state = () => ({
  articleList: [],
  detail: {},
  totalPage: 0,
  currentPage: 1,
  viewCount: 0,
  opinion: {
    very_good: {
      src: require('~/assets/images/like_love.png'),
      isShowLaoding: false,
      text: 'Love'
    },
    good: {
      src: require('~/assets/images/like_haha.png'),
      isShowLaoding: false,
      text: 'Haha'
    },
    commonly: {
      src: require('~/assets/images/like_wow.png'),
      isShowLaoding: false,
      text: 'Wow'
    },
    bad: {
      src: require('~/assets/images/like_sad.png'),
      isShowLaoding: false,
      text: 'Sad'
    },
    very_bad: {
      src: require('~/assets/images/like_angry.png'),
      isShowLaoding: false,
      text: 'Angry'
    }
  }
})

export const mutations = {
  [UPDATE_ARTICLE_LIST] (state, data) {
    state.articleList = data
  },

  [SET_ARTICLE_DETAIL] (state, data) {
    state.detail = data
  },

  [SET_TOTAL_PAGE] (state, n) {
    state.totalPage = n
  },

  [SET_CURRENT_PAGE] (state, n) {
    state.currentPage = n
  },

  [UPDATE_VIEW_COUNT] (state, n) {
    state.viewCount = n
  },

  [UPDATE_OPINION] (state, data) {
    Object.keys(state.opinion).map(key => {
      state.opinion[key].data = data[key]
    })
  },

  [UPDATE_OPINION_LOADING] (state, { key, flag }) {
    state.opinion[key].isShowLaoding = flag
  }
}

export const actions = {
  // 获取文章列表
  async getArticleList ({ rootState, commit }, requestData) {
    try {
      const { data, headers } = await this.$axios.$get(`${process.env.baseUrl}/wp-json/wp/v2/posts`, {
        params: requestData,
        data: { progress: false }
      })
      data.map(item => {
        item.articleInfor.thumbnail = item.articleInfor.thumbnail ? item.articleInfor.thumbnail.replace(/https?:\/\/(\w+\.)+\w+(:\d+)?/, '') : rootState.info.thumbnail
        item.date = item.date.replace('T', ' ')
      })
      commit(UPDATE_ARTICLE_LIST, data)
      commit(SET_TOTAL_PAGE, +headers['x-wp-total'])
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 获取文章详情
  async getArticleDetail ({ commit, rootState }, id) {
    try {
      const domainRegexp = /(https?:\/\/([a-z\d-]\.?)+(:\d+)?)?(\/.*)/gi
      const { data } = await this.$axios.$get(`${process.env.baseUrl}/wp-json/wp/v2/posts/${id}`, {
        data: { progress: false }
      })
      data.date = data.date.replace('T', ' ')
      data.articleInfor.other.authorPic = data.articleInfor.other.authorPic.replace(domainRegexp, '$4')
      data.articleInfor.thumbnail = data.articleInfor.thumbnail
        ? data.articleInfor.thumbnail.replace(domainRegexp, '$4')
        : rootState.info.thumbnail
      commit(SET_ARTICLE_DETAIL, data)
      commit(UPDATE_OPINION, data.articleInfor.xmLike)
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 更新阅读量
  async updateArticleViewCount ({ commit }, requestData) {
    try {
      const { data } = await this.$axios.$post(`${process.env.baseUrl}/wp-json/xm-blog/v1/view-count`, requestData, {
        headers: {
          progress: false
        }
      })
      commit(UPDATE_VIEW_COUNT, data)
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 发表意见
  async updateOpinion ({ commit }, requestData) {
    try {
      const { data } = await this.$axios.$post(`${process.env.baseUrl}/wp-json/xm-blog/v1/like`, requestData, {
        headers: {
          progress: false
        }
      })
      commit(UPDATE_OPINION, data)
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  }
}
