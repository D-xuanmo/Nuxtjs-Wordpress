import Vue from 'vue'
import Vuex from 'vuex'
import axios from '~/plugins/axios'

Vue.use(Vuex)

const store = () => new Vuex.Store({
  state: {
    info: {},
    menu: {}
  },

  mutations: {
    getInfo (state, { info, menu }) {
      state.info = info,
      state.menu = menu
    }
  }
})

export default store
