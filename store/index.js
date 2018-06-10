import Vue from 'vue'
import Vuex from 'vuex'
import axios from '~/plugins/axios'

Vue.use(Vuex)

const store = () => new Vuex.Store({
  state: {
    info: {}
  },

  mutations: {
    getInfo (state, params) {
      return state.info = params
    }
  }
})

export default store
