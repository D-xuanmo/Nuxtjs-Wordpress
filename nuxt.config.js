module.exports = {
  /*
  ** Headers of the page
  */
  head: {
    title: 'xm-nuxt-wordpress-theme',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.png' }
    ],
    script: [
      { src: '//at.alicdn.com/t/font_556506_fosg14c49ehtzkt9.js' }
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
    // npm install @nuxtjs/proxy -D
    ['@nuxtjs/proxy']
  ],

  // 配置代理
  axios: {
    proxy: true
  },

  proxy: {
    '/api': {
      // target: 'http://localhost:8888',
      target: 'https://www.xuanmo.xin',
      pathRewrite: {
        '^/api': '/'
      }
    }
  },

  env: {
    // baseUrl: 'http://localhost:8888'
    baseUrl: 'https://www.xuanmo.xin'
  }
}
