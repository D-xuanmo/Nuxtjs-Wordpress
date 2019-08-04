class OpeartionDOM {
  constructor (selector, parent) {
    if (typeof selector === 'object') {
      this.$el = selector
    } else {
      const el = parent.querySelectorAll(selector)
      this.$el = el.length > 1 ? el : el[0]
    }
    // return this.$el
  }

  _getStyle (attr) {
    return window.getComputedStyle(this.$el)[attr]
  }

  _styleToNumber (attr) {
    return parseFloat(this._getStyle(attr))
  }

  // 判断是否为多个元素
  _isSelectors () {
    return this.$el.length > 1
  }

  width () {
    if (arguments.length) {
      this.$el.style.width = arguments[0]
      return this
    } else {
      return this.$el.offsetWidth
    }
  }

  height () {
    if (arguments.length) {
      this.$el.style.height = arguments[0]
      return this
    } else {
      return this.$el.offsetHeight
    }
  }

  outerWidth (bool) {
    const margin = this._styleToNumber('marginRight') + this._styleToNumber('marginLeft')
    const padding = this._styleToNumber('paddingRight') + this._styleToNumber('paddingLeft')
    const border = this._styleToNumber('borderRight') + this._styleToNumber('borderLeft')
    return bool ? this.$el.offsetWidth + margin + padding + border : this.$el.offsetWidth + padding + border
  }

  outerHeight (bool) {
    const margin = this._styleToNumber('marginTop') + this._styleToNumber('marginBottom')
    const padding = this._styleToNumber('paddingTop') + this._styleToNumber('paddingBottom')
    const border = this._styleToNumber('borderTop') + this._styleToNumber('borderBottom')
    return bool ? this.$el.offsetHeight + margin + padding + border : this.$el.offsetHeight + padding + border
  }

  parent () {
    return new OpeartionDOM(this.$el.parentNode)
  }

  prev () {
    return new OpeartionDOM(this.$el.previousElementSibling)
  }

  children () {
    return this.$el.children
  }

  siblings () {
    if (!this.$el.parentNode) return this
    this.$el = [].filter.call(this.$el.parentNode.children, child => child !== this.$el)
    return this
  }

  hasClass (className) {
    return this.$el.classList.contains(className)
  }

  removeClass (className) {
    if (this._isSelectors()) {
      this.$el.forEach(el => {
        className.split(' ').map(name => el.classList.remove(name))
      })
    } else {
      className.split(' ').map(name => this.$el.classList.remove(name))
    }
    return this
  }

  addClass (className) {
    if (this._isSelectors()) {
      this.$el.forEach(el => {
        className.split(' ').map(name => el.classList.add(name))
      })
    } else {
      className.split(' ').map(name => this.$el.classList.add(name))
    }
    return this
  }

  toggleClass (className) {
    className.split(' ').map(name => this.$el.classList.toggle(name))
  }

  css (attr, value) {
    if (typeof attr === 'object') {
      for (let [_key, _value] of Object.entries(attr)) {
        this.$el.style[_key] = _value
      }
    } else {
      this.$el.style[attr] = value
    }
    return this
  }

  attr (attr, value) {
    if (value) {
      this.$el[attr] = value
      return this
    } else {
      return this.$el.getAttribute(attr)
    }
  }

  html () {
    return this.$el.innerHTML
  }
}

const $ = (el, parent = document) => new OpeartionDOM(el, parent)

export default $
