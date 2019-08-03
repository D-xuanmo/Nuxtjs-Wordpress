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
function replace_domain($url)
{
    return preg_replace("/https?:\/\/(\w+\.)+\w+(:\d+)?/", "", $url);
}


function get_IP()
{
    $ip = false;

    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);

        if ($ip) {
            array_unshift($ips, $ip);
            $ip = false;
        }

        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])) {
                $ip = $ips[$i];

                break;
            }
        }
    }

    return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
