import Vue from 'vue'
import Component from './main'
const VueMessage = Vue.extend(Component)
let message = null
const Message = {
  install (Vue) {
    const messageFn = (opt) => {
      message = new VueMessage().$mount()
      document.querySelector('body').appendChild(message.$el)
      message.show(opt, true)
    }
    Vue.message = Vue.prototype.$message = messageFn
  }
}

export default Message
