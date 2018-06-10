module.exports = {
  /*
  ** Headers of the page
  */
  head: {
    title: 'xm-nuxt-wordpress-theme',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'Nuxt.js project' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.png' }
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

    // 打包静态资源路径
    // publicPath: '/nuxt',

    vendors: ['axios'],

    // 全局引入scss
    styleResources: {
      scss: './assets/scss/variable.scss'
    }
  },

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
      target: 'http://localhost:8888',
      pathRewrite: {
        '^/api': '/'
      }
    }
  },

  env: {
    baseUrl: 'http://localhost:8888'
    // baseUrl: 'http://blog.xuanmo.xin'
  }
}
