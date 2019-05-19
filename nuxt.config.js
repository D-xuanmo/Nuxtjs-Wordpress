module.exports = {
  mode: 'universal',
  /*
   ** Headers of the page
   */
  head: {
    title: 'Xuanmo Blog | WEB前端笔记',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { hid: 'prism', rel: 'stylesheet', href: 'https://upyun.xuanmo.xin/css/prism.css' }
    ],
    script: [
      { src: '//at.alicdn.com/t/font_556506_8c5mvyqjye4.js' },
      { src: 'https://upyun.xuanmo.xin/js/prism.js' },
      // 百度主推文章收录用
      { src: 'https://zz.bdstatic.com/linksubmit/push.js' },
      // 加入百度统计js，使用时自行添加为自己的
      { src: 'https://hm.baidu.com/hm.js?' }
    ]
  },

  router: {
    middleware: 'info',
    scrollBehavior (to, from, savedPosition) {
      return {
        x: 0,
        y: 0
      }
    }
  },

  /*
   ** Customize the progress bar color
   */
  loading: './components/Loading',

  css: [
    './assets/scss/global.scss'
  ],

  /*
   ** Build configuration
   */
  build: {
    babel: {
      plugins: [
        [
          'component',
          {
            libraryName: 'element-ui',
            styleLibraryName: 'theme-chalk'
          }
        ]
      ]
    },
    extractCSS: true,
    vendors: ['axios', 'element-ui']
  },

  plugins: [
    { src: '~/plugins/element-ui', ssr: true },
    { src: '~/plugins/message', ssr: false },
    { src: '~/plugins/icon', ssr: true }
  ],

  modules: ['@nuxtjs/proxy', '@nuxtjs/style-resources'],

  styleResources: {
    scss: ['./assets/scss/variable.scss']
  },

  // 配置代理
  axios: {
    proxy: true
  },

  // 将此处3个地址改为自己的地址
  proxy: {
    '/api': {
      target: 'http://localhost:8888',
      pathRewrite: {
        '^/api': '/'
      }
    },
    '/wp-content': {
      target: 'http://localhost:8888'
    }
  },

  env: {
    baseUrl: 'http://localhost:8888'
  }
}
