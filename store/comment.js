import {
  SET_COMMENT_LIST,
  SET_COMMENT_TOTAL,
  UPDATE_COMMENT,
  SET_EXPRESSION,
  UPDATE_COMMENT_OPINION
} from './mutations-types'

export const state = () => ({
  commentList: [],
  totalPage: 0,
  expressionList: []
})

export const mutations = {
  [SET_COMMENT_LIST] (state, data) {
    state.commentList = [...state.commentList, ...data]
  },

  [SET_COMMENT_TOTAL] (state, n) {
    state.totalPage = n
  },

  [UPDATE_COMMENT] (state, data) {
    state.commentList.unshift(data)
  },

  [UPDATE_COMMENT_OPINION] (state, { index, data }) {
    state.commentList[index].meta.opinion = data
  },

  [SET_EXPRESSION] (state, data) {
    state.expressionList = data
  }
}

export const actions = {
  // 获取评论列表
  async getCommentList ({ commit }, requestData) {
    try {
      let { data, headers } = await this.$axios.$get(`${process.env.baseUrl}/wp-json/wp/v2/comments`, {
        params: requestData,
        data: { progress: false }
      })
      commit(SET_COMMENT_LIST, data)
      commit(SET_COMMENT_TOTAL, +headers['x-wp-totalpages'])
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 提交评论
  async updateComment ({ commit }, requestData) {
    try {
      let { data } = await this.$axios.$post(`${process.env.baseUrl}/wp-json/wp/v2/comments`, {
        progress: false,
        ...requestData
      })
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 获取表情列表
  async getExpression ({ commit }, domain) {
    try {
      let { data } = await this.$axios.$get(`${domain}/expression.php`, {
        data: { progress: false }
      })
      commit(SET_EXPRESSION, data)
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 评论列表点赞
  async updateCommentOpinion ({ commit }, requestData) {
    try {
      let { data } = await this.$axios.$post(`${process.env.baseUrl}/wp-json/xm-blog/v1/update-comment-meta`, {
        progress: false,
        ...requestData
      })
      return data.success ? Promise.resolve(data.data) : Promise.reject(new Error('请求异常！'))
    } catch (error) {
      return Promise.reject(error)
    }
  }
}
