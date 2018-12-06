import Vue from 'vue'
import Vuex from 'vuex'
import API from '~/api'
import { GLOBAL_INFORMATION, ERROR } from './mutation-type'

Vue.use(Vuex)

const store = () => new Vuex.Store({
  state: {
    info: {},
    menu: {},
    subMenu: {},
    errorInformation: {
      statusCode: '',
      message: ''
    }
  },

  mutations: {
    [GLOBAL_INFORMATION] (state, { info, menu, subMenu }) {
      state.info = info
      state.menu = menu
      state.subMenu = subMenu
    },
    [ERROR] (state, { code, message }) {
      console.log(code)
      state.errorInformation.statusCode = code
      state.errorInformation.message = message
    }
  },

  actions: {
    updateError ({ commit }, { code, message }) {
      commit('ERROR', { code, message })
    }
  }
})

export default store
