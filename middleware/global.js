export default function ({ store }) {
  store.commit('UPDATE_MENU_STATUS', false)
  store.commit('CLOSE_READING_MODE')
}
