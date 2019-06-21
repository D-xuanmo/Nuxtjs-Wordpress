var $ = jQuery;
$(function() {
  var imgUrl, input;
  $('.choose-image').click(function() {
    input = $(this).siblings('input');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
  });

  window.send_to_editor = function(img) {
    imgUrl = $(img).attr('src');
    $(input).val(imgUrl);
    $(input).siblings('p').children('.preview-img').attr('src', imgUrl);
    tb_remove();
  }

  // 导航切换
  $('.tab-wrapper .line').css('left', $('.tab-wrapper .item:first').position().left + 'px').width($('.tab-wrapper .item:first').outerWidth(true) + 'px');
  $('.tab-wrapper .item').click(function() {
    var $this = $(this);
    $this.addClass('is-active').siblings('.item').removeClass('is-active');
    $('.tab-wrapper .line').css('left', $this.position().left + 'px').width($this.outerWidth(true) + 'px');
    $('.content-item').eq($this.index()).show().siblings('.content-item').hide();
  });
});
