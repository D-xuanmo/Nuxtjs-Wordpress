import { SET_PHRASE_LIST } from './mutations-types'

export const state = () => ({
  list: []
})

export const mutations = {
  [SET_PHRASE_LIST] (state, data) {
    state.list = data
  }
}

export const actions = {
  async getPhraseList ({ commit }) {
    try {
      let { data } = await this.$axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/get-phrase`)
      commit(SET_PHRASE_LIST, data.data.data)
      return Promise.resolve(data.data.data)
    } catch (error) {
      return Promise.reject(error)
    }
  }
}
