import Vue from 'vue'
import axios from 'axios'
// import qs from 'qs'

// 设置请求超时
axios.defaults.timeout = 20000

// 生产环境和发布环境给出不同的接口地址
axios.defaults.baseURL = '/api'
// if (process.server) {
//   axios.defaults.baseURL = `http://${process.env.HOST || 'localhost'}:${process.env.PORT || 5200}`
// }

// 设置数据格式
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'

// 转换数据
// axios.defaults.transformRequest = (data) => qs.stringify(data)

// 添加请求拦截器
axios.interceptors.request.use(config => {
  return config
}, error => {
  return Promise.reject(error)
})

// 添加响应拦截器
axios.interceptors.response.use(response => {
  return {
    data: response.data,
    headers: response.headers,
    status: response.status,
    statusText: response.statusText
  }
}, error => {
  return Promise.reject(error)
})

export default axios.create()
