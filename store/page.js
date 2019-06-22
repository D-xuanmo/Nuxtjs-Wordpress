import { SET_CURRENT_PAGE } from './mutations-types'

export const state = () => ({
  detail: {}
})

export const mutations = {
  [SET_CURRENT_PAGE] (state, data) {
    state.detail = data
  }
}

export const actions = {
  async getPageDetail ({ commit }, id) {
    try {
      let { data } = await this.$axios.$get(`${process.env.baseUrl}/wp-json/wp/v2/pages/${id}`, {
        data: { progress: false }
      })
      commit(SET_CURRENT_PAGE, data)
      return Promise.resolve(data)
    } catch (error) {
      return Promise.reject(error)
    }
  }
}
