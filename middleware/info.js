import axios from '~/plugins/axios'
export default async function (context) {
  let [info, menu] = await Promise.all([
    axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/info`),
    axios.get(`${process.env.baseUrl}/wp-json/xm-blog/v1/menu`)
  ])
  context.store.commit('getInfo', {
    info: info.data,
    menu: menu.data.mainMenu,
    subMenu: menu.data.subMenu
  })
}
