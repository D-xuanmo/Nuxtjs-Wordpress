import Vue from 'vue'
import msgConponent from './Message'
const VueMessage = Vue.extend(msgConponent)
let message = null
let oWrap = document.createElement('div')
oWrap.className = 'xm-message-wrap'
oWrap.style = 'position: fixed; top: 30px; left: 0; z-index: 99999; width: 100%;'
const Message = {
  install (Vue, opt) {
    let messageFn = (opt) => {
      message = new VueMessage().$mount()
      document.querySelector('body').appendChild(oWrap)
      oWrap.appendChild(message.$el)
      message.show(opt, true)
    }
    Vue.message = Vue.prototype.$message = messageFn
  }
}

export default Message
