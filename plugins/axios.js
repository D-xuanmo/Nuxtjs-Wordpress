import Axios from 'axios'
import qs from 'qs'

const option = {
  timeout: 20000,
  baseURL: process.env.NODE_ENV === 'production' ? '' : '/api',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded'
  },
  transformRequest: data => qs.stringify(data)
}

const axios = Axios.create(option)

// 添加请求拦截器
axios.interceptors.request.use(config => config, error => Promise.reject(error))

// 添加响应拦截器
axios.interceptors.response.use(response => {
  return {
    data: response.data,
    status: response.status,
    headers: response.headers,
    statusText: response.statusText
  }
}, error => Promise.reject(error))

export default axios
