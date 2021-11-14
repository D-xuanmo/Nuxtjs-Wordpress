export default function ({ store }) {
  store.commit('UPDATE_MENU_STATUS', false)
  store.commit('TOGGLE_READING_MODE')
}
