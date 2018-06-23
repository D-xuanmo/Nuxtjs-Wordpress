jQuery(function() {
  var imgurl,
    targetfield;
  jQuery('input[type="button"]').click(function() {
    targetfield = jQuery(this).siblings('input');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
  });

  window.send_to_editor = function(obj) {
    imgurl = jQuery('img', obj).attr('src') || jQuery(obj).attr('src');
    jQuery(targetfield).val(imgurl);
    jQuery(targetfield).parent().parent().siblings().find('.preview-img').attr('src', imgurl);
    tb_remove();
  }

  // 导航切换
  jQuery('.nav-list').click(function() {
    jQuery(this).addClass('on').siblings().removeClass('on');
    jQuery('.content-wrap').eq(jQuery(this).index()).show().siblings('.content-wrap').hide();
  });
});
