# xm-nuxt-wordpress

> 使用[Nuxtjs](https://nuxtjs.org) + [Wordpress Rest Api](https://developer.wordpress.org/rest-api/)实现前后端分离，服务端渲染页面

> `service`目录为wordpress主题文件，需要拷贝到wordpress主题目录下，`wp-content/themes`

> 使用的时候请修改`nuxt.config.js`的配置项，改为自己的信息，修改注释的地方即可

> 修改`assets/scss/variable.scss`文件下的`$color-theme`变量，可以更换博客的主体风格

> 原创不易，希望能够保留右下角的主题作者，谢谢

## 主题设置截图

![](https://upyun.xuanmo.xin/blog/xm-nuxt-wordpress-1.png)

![](https://upyun.xuanmo.xin/blog/xm-nuxt-wordpress-2.png)

![](https://upyun.xuanmo.xin/blog/xm-nuxt-wordpress-3.png)

## Build Setup

``` bash
# install dependencies
$ yarn

# serve with hot reload at localhost:3000
$ yarn dev

# build for production and launch server
$ yarn build
$ yarn start

# generate static project
$ yarn generate
```

For detailed explanation on how things work, checkout the [Nuxt.js docs](https://github.com/nuxt/nuxt.js).
