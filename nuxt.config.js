module.exports = {
  /*
  ** Headers of the page
  */
  head: {
    title: 'Xuanmo Blog',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ],
    script: [
      { src: 'https://at.alicdn.com/t/font_556506_fosg14c49ehtzkt9.js' },
      { src: 'https://upyun.xuanmo.xin/js/prism.js' }
    ]
  },

  /*
  ** Customize the progress bar color
  */
  loading: { color: '#06aaff' },

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

    // element-ui按需引入配置
    babel: {
      'plugins': [['component', [
        {
          'libraryName': 'element-ui',
          'styleLibraryName': 'theme-default'
        },
        'transform-async-to-generator',
        'transform-runtime'
      ]]],
      comments: true
    },

    vendors: ['axios', 'element-ui'],

    // 全局引入scss
    styleResources: {
      scss: './assets/scss/variable.scss'
    }
  },

  plugins: [
    { src: '~/plugins/element-ui', ssr: true },
    { src: '~/plugins/message', ssr: false }
  ],

  modules: [
    // yarn add @nuxtjs/proxy -D
    ['@nuxtjs/proxy']
  ],

  // 配置代理
  axios: {
    proxy: true
  },

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
