import axios from '~/plugins/axios'

export default {
  // 获取公用信息
  getGlobalInfomation (params) {
    return axios({
      url: `${process.env.baseUrl}/wp-json/xm-blog/v1/info`,
      params
    })
  },

  // 获取菜单
  getMenuList (params) {
    return axios({
      url: `${process.env.baseUrl}/wp-json/xm-blog/v1/menu`,
      params
    })
  },

  // 获取文章列表
  getArticleList (params) {
    return axios({
      url: `${process.env.baseUrl}/wp-json/wp/v2/posts`,
      params
    })
  },

  // 获取文章详情
  getArticleDetails (id) {
    return axios({
      url: `${process.env.baseUrl}/wp-json/wp/v2/posts/${id}`
    })
  },

  // 获取文章详情
  getPageDetails (id) {
    return axios({
      url: `${process.env.baseUrl}/wp-json/wp/v2/pages/${id}`
    })
  },

  // 更新阅读量
  updateViewCount (data) {
    return axios({
      url: `${process.env.baseUrl}/wp-json/xm-blog/v1/view-count`,
      method: 'POST',
      data
    })
  },

  // 发表意见
  updateArticleLike (data) {
    return axios({
      url: `${process.env.baseUrl}/wp-json/xm-blog/v1/link/`,
      method: 'POST',
      data
    })
  },

  // 获取评论列表
  getCommentList (params) {
    return axios({
      url: '/wp-json/wp/v2/comments',
      params
    })
  },

  // 发表评论
  updateComment (data) {
    return axios({
      url:'/wp-json/wp/v2/comments',
      method: 'POST',
      data
    })
  },

  // 获取表情列表
  getExpression (domain, params) {
    return axios({
      url: `${domain}/expression.php`,
      params
    })
  },

  // 上传图片
  uploadImage (data, config) {
    return axios({
      url: '/wp-content/themes/xm-vue-theme/xm_upload.php',
      method: 'POST',
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      data,
      ...config
    })
  },

  // 删除图片
  deleteImage (data) {
    return axios({
      url: '/wp-content/themes/xm-vue-theme/xm_upload.php',
      method: 'POST',
      data
    })
  }
}
