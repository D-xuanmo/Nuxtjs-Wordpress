import Vue from 'vue'
import msgConponent from './Message'
const VueMessage = Vue.extend(msgConponent)
let message = null
const Message = {
  install (Vue, opt = {}) {
    let messageFn = (opt) => {
      message = new VueMessage().$mount()
      document.querySelector('body').appendChild(message.$el)
      message.show(opt, true)
    }
    Vue.message = Vue.prototype.$message = messageFn
  }
}

export default Message
