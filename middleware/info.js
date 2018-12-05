import API from '~/api'

export default async function (context) {
  let [info, menu] = await Promise.all([API.getGlobalInfomation(), API.getMenuList()])
  context.store.commit('getInfo', {
    info: info.data,
    menu: menu.data.mainMenu,
    subMenu: menu.data.subMenu
  })
}
