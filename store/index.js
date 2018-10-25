import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = () => new Vuex.Store({
  state: {
    info: {},
    menu: {},
    subMenu: {}
  },

  mutations: {
    getInfo (state, { info, menu, subMenu }) {
      state.info = info,
      state.menu = menu
      state.subMenu = subMenu
    }
  }
})

export default store
