document.body.addEventListener('touchstart', () => {})
document.body.addEventListener('click', (e) => {
  {
    const moveDotWrap = document.createElement('div')
    const expression = [
      'https://upyun.xuanmo.xin/images/smilies/huaixiao.gif',
      'https://upyun.xuanmo.xin/images/smilies/yinxian.gif',
      'https://upyun.xuanmo.xin/images/smilies/xieyanxiao.gif',
      'https://upyun.xuanmo.xin/images/smilies/xiaoku.gif',
      'https://upyun.xuanmo.xin/images/smilies/ciya.gif',
      'https://upyun.xuanmo.xin/images/smilies/wozuimei.gif',
      'https://upyun.xuanmo.xin/images/smilies/koubi.gif'
    ]
    moveDotWrap.className = 'move-dot-wrap on'
    moveDotWrap.style.top = e.clientY + 'px'
    moveDotWrap.style.left = e.clientX + 'px'
    const oImg = new Image()
    oImg.src = expression[parseInt(Math.random() * expression.length)]
    moveDotWrap.appendChild(oImg)
    document.body.appendChild(moveDotWrap)
    setTimeout(() => moveDotWrap.remove(), 1000)
  }
})
