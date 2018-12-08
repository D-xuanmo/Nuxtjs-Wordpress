module.exports = {
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
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ],
    script: [
      { src: '//at.alicdn.com/t/font_556506_sucn34hulmj.js' },
      { src: 'https://upyun.xuanmo.xin/js/prism.js' },
      // 百度主推文章收录用
      { src: 'https://zz.bdstatic.com/linksubmit/push.js' },
      // 加入百度统计js，使用时自行添加为自己的
      { src: 'https://hm.baidu.com/hm.js?e44a328f25e7df044d47bfe1676d69ac' }
    ]
  },

  router: {
    middleware: 'info',
    scrollBehavior (to, from, savedPosition) {
      return { x: 0, y: 0 }
    }
  },

  /*
  ** Customize the progress bar color
  */
  loading: './components/loading',

  css: [
    './assets/scss/global.scss'
  ],

  /*
  ** Build configuration
  */
  build: {
    /*
    ** Run ESLint on save
    */
    extend (config, { isDev, isClient }) {
      if (isDev && isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    },

    extractCSS: {
      allChunks: true
    },

    // element-ui按需引入配置
    babel: {
      'plugins': [
        [
          'component',
          [
            {
              'libraryName': 'element-ui',
              'styleLibraryName': 'theme-default'
            },
            'transform-async-to-generator',
            'transform-runtime'
          ]
        ]
      ],
      comments: true
    },

    vendors: ['axios', 'element-ui'],

    // 全局引入scss
    styleResources: {
      scss: ['./assets/scss/variable.scss']
    }
  },

  plugins: [
    { src: '~/plugins/element-ui', ssr: true },
    { src: '~/plugins/message', ssr: false },
    { src: '~/plugins/icon', ssr: true }
  ],

  modules: [
    // npm install @nuxtjs/proxy -D
    ['@nuxtjs/proxy']
  ],

  // 配置代理
  axios: {
    proxy: true
  },

  proxy: {
    '/api': {
      target: 'https://www.xuanmo.xin',
      pathRewrite: {
        '^/api': '/'
      }
    },
    '/wp-content': {
      target: 'https://www.xuanmo.xin'
    }
  },

  env: {
    baseUrl: 'https://www.xuanmo.xin'
  }
}
