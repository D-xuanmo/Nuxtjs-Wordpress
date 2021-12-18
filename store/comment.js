import {
  SET_COMMENT_LIST,
  SET_COMMENT_TOTAL,
  UPDATE_COMMENT,
  SET_EXPRESSION,
  UPDATE_COMMENT_OPINION,
  RESET_COMMENT,
  SET_COMMENT_CURRENT_FORM_ID
} from './mutations-types'

const recursionModifyDataById = (arr, id, value, key) => {
  return arr.map(item => {
    if (item.id === id) {
      if (key) {
        item[key] = value
      } else {
        item = value
      }
      return item
    } else {
      item.children = item.children.length ? recursionModifyDataById(item.children, id, value, key) : []
      return item
    }
  })
}

export const state = () => ({
  commentList: [],
  totalPage: 0,
  expressionList: [],
  commentFormId: '0'
})

export const mutations = {
  [SET_COMMENT_LIST](state, data) {
    state.commentList = [...state.commentList, ...data]
  },

  [RESET_COMMENT](state) {
    state.commentList = []
    state.totalPage = 0
  },

  [SET_COMMENT_TOTAL](state, n) {
    state.totalPage = n
  },

  [UPDATE_COMMENT](state, { data, type, id }) {
    if (type === 'unshift') {
      state.commentList.unshift(data)
    } else if (type === 'replace') {
      const index = state.commentList.findIndex((item) => item.id === id)
      state.commentList.splice(index, 1, data)
    } else if (type === 'merge') {
      const index = state.commentList.findIndex((item) => item.id === id)
      state.commentList.splice(index, 1, {
        ...state.commentList[index],
        ...data
      })
    }
  },

  [UPDATE_COMMENT_OPINION](state, { id, data }) {
    state.commentList = recursionModifyDataById(state.commentList, id, data, 'opinion')
  },

  [SET_EXPRESSION](state, data) {
    state.expressionList = data
  },

  [SET_COMMENT_CURRENT_FORM_ID](state, id) {
    state.commentFormId = id
  }
}

export const actions = {
  // 获取评论列表
  async getCommentList({ commit }, requestData) {
    try {
      const {
        data,
        totalPage
      } = await this.$axios.$get(`${process.env.baseUrl}/wp-json/xm/v2/comment/list`, {
        params: requestData,
        data: { progress: false }
      })
      commit(SET_COMMENT_LIST, data.map(item => ({ ...item, loading: false })))
      commit(SET_COMMENT_TOTAL, totalPage)
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  async getSingleComment({ commit }, requestData) {
    try {
      commit(UPDATE_COMMENT, {
        data: {
          loading: true
        },
        type: 'merge',
        id: requestData.commentId
      })
      const { data } = await this.$axios.$get(`${process.env.baseUrl}/wp-json/xm/v2/comment/list/single`, {
        params: requestData,
        data: { progress: false }
      })
      commit(UPDATE_COMMENT, {
        data: {
          ...data[0],
          hasChildren: false
        },
        type: 'replace',
        id: requestData.commentId
      })
      return Promise.resolve(data)
    } catch (error) {
      commit(UPDATE_COMMENT, {
        data: {
          loading: false
        },
        type: 'merge',
        id: requestData.commentId
      })
      return Promise.reject(error)
    }
  },

  // 提交评论
  async updateComment({ commit }, requestData) {
    try {
      const { data } = await this.$axios.$post(`${process.env.baseUrl}/wp-json/wp/v2/comments`, requestData, {
        headers: {
          progress: false
        }
      })
      commit(UPDATE_COMMENT, {
        data: data.newComment,
        type: 'unshift'
      })
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 获取表情列表
  async getExpression({ commit, rootState }) {
    try {
      const { data } = await this.$axios.$get(`${rootState.info.templeteUrl}/expression.php`, {
        data: { progress: false }
      })
      commit(SET_EXPRESSION, data)
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  },

  // 评论列表点赞
  async updateCommentOpinion({ commit }, requestData) {
    try {
      const { data } = await this.$axios.$post(`${process.env.baseUrl}/wp-json/xm-blog/v1/update-comment-meta`, requestData, {
        headers: {
          progress: false
        }
      })
      commit(UPDATE_COMMENT_OPINION, {
        id: requestData.id,
        data: data.data
      })
      return data.success ? Promise.resolve(data.data) : Promise.reject(data)
    } catch (error) {
      return Promise.reject(error)
    }
  }
}
