class OpeartionDOM {
  constructor (selector) {
    if (typeof selector === 'object') {
      this.el = selector
    } else {
      const el = document.querySelectorAll(selector)
      this.el = el.length > 1 ? el : el[0]
    }
  }

  _getStyle (attr) {
    return window.getComputedStyle(this.el)[attr]
  }

  _styleToNumber (attr) {
    return parseFloat(this._getStyle(attr))
  }

  width () {
    if (arguments.length) {
      this.el.style.width = arguments[0]
      return this
    } else {
      return this.el.offsetWidth
    }
  }

  height () {
    if (arguments.length) {
      this.el.style.height = arguments[0]
      return this
    } else {
      return this.el.offsetHeight
    }
  }

  outerWidth (bool) {
    const margin = this._styleToNumber('marginRight') + this._styleToNumber('marginLeft')
    const padding = this._styleToNumber('paddingRight') + this._styleToNumber('paddingLeft')
    const border = this._styleToNumber('borderRight') + this._styleToNumber('borderLeft')
    return bool ? this.el.offsetWidth + margin + padding + border : this.el.offsetWidth + padding + border
  }

  outerHeight (bool) {
    const margin = this._styleToNumber('marginTop') + this._styleToNumber('marginBottom')
    const padding = this._styleToNumber('paddingTop') + this._styleToNumber('paddingBottom')
    const border = this._styleToNumber('borderTop') + this._styleToNumber('borderBottom')
    return bool ? this.el.offsetHeight + margin + padding + border : this.el.offsetHeight + padding + border
  }

  children () {
    return this.el.children
  }

  parent () {
    return new OpeartionDOM(this.el.parentElement)
  }

  prev () {
    return new OpeartionDOM(this.el.previousElementSibling)
  }

  hasClass (className) {
    return this.el.className.match(new RegExp(className, 'g')) !== null
  }

  removeClass (className) {
    if (!this.hasClass(className)) return this
    this.el.className = this.el.className.replace(new RegExp(`\\s${className}`, 'g'), '')
    return this
  }

  addClass (className) {
    if (this.hasClass(className)) return this
    this.el.className += ` ${className}`
    return this
  }

  css (attr, value) {
    if (typeof attr === 'object') {
      for (let [_key, _value] of Object.entries(attr)) {
        this.el.style[_key] = _value
      }
    } else {
      this.el.style[attr] = value
    }
    return this
  }

  attr (attr, value) {
    if (value) {
      this.el[attr] = value
      return this
    } else {
      return this.el[attr]
    }
  }

  html () {
    return this.el.innerHTML
  }
}
const $ = el => new OpeartionDOM(el)
export default $
