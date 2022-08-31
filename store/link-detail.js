import { UPDATE_INNER_LINK_LIST, UPDATE_LINK_DETAIL } from '~/store/mutations-types'

export const state = () => ({
  detail: {},
  innerList: []
})

export const mutations = {
  [UPDATE_LINK_DETAIL](state, data) {
    state.detail = data
  },
  [UPDATE_INNER_LINK_LIST](state, data) {
    state.innerList = data
  }
}

export const actions = {
  async getLinkPageDetail({ commit }) {
    try {
      const { data } = await this.$axios.get(`${process.env.baseUrl}/wp-json/xm/v2/link/page/detail`)
      const { data: links } = await this.$axios.get(`${process.env.baseUrl}/wp-json/xm/v2/links?category=${encodeURI('友情链接')}`)
      commit(UPDATE_LINK_DETAIL, data.data)
      commit(UPDATE_INNER_LINK_LIST, links.data)
      return Promise.resolve(data.data)
    } catch (error) {
      return Promise.reject(error)
    }
  }
}
