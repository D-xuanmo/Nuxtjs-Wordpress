const fs = require('fs')
const exec = require('child_process').exec

fs.watch(`${__dirname}/service`, { recursive: true }, (eventType, fileName) => {
  const time = new Date(+new Date() + 8 * 3600 * 1000).toISOString().replace(/T/g, ' ').replace(/\.[\d]{3}Z/, '')
  console.log(time, fileName)
  exec('cp -r service/* /Applications/MAMP/htdocs/wp-content/themes/xm-vue-theme', err => err && console.log(err))
})
