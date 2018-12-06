import API from '~/api'

export default async function ({ store }) {
  let [info, menu] = await Promise.all([API.getGlobalInfomation(), API.getMenuList()])
  store.dispatch('updateError', { code: '', message: '' })
  store.commit('GLOBAL_INFORMATION', {
    info: info.data,
    menu: menu.data.mainMenu,
    subMenu: menu.data.subMenu
  })
}
