<?php
/**
 * 输出表情
 *
 * @param [string] $str 需要转换表情的字符串
 * @return 转换后的字符串
 */
function xm_output_smiley($str = "")
{
  return preg_replace_callback("/\[(?!img)\w+\]/", function ($matchs) {
    global $wpsmiliestrans;
    $smilies_dir = get_option("xm_vue_options")["domain"] . '/images/smilies/';
    return '<img src="'.$smilies_dir.$wpsmiliestrans[$matchs[0]].'" width="20" style="vertical-align:baseline;box-shadow:none;">';
  }, $str);
}

/**
 * 需要替换的地址
 *
 * @param [type] $url 需要替换的地址，示例：https://www.xuanmo.xin/wp-json
 * @return 返回替换后的地址，示例：/wp-json
 */
function replace_domain ($url) {
  return preg_replace("/https?:\/\/(\w+\.)+\w+(:\d+)?/", "", $url);
}
?>
