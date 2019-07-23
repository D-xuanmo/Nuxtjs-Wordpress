<?php
// 判断浏览器型号
function get_browser_name ($str) {
  // $matches['ua'] = $str;
  // 判断系统
  if (preg_match('/Maci/', $str)) {
    // mac
    preg_match_all('/(?P<system>Mac(\s\w+)+\s(?P<version>(\d+\.\d+)|\d+(_\d+){2}))/i', $str, $match, PREG_SET_ORDER);
    $matches['system'] = 'Mac ' . $match[0]['version'];
  } else if (preg_match('/iPad|iPhone/', $str)) {
    // iPad or iphone
    preg_match_all('/(?P<system>\w+);(\s[a-zA-Z]+)+\s(?P<version>\d+_\d+)/i', $str, $match, PREG_SET_ORDER);
    $matches['system'] = $match[0]['system'] . ' ' . $match[0]['version'];
  } else if (preg_match('/Android/', $str)) {
    // Android
    preg_match_all('/(?P<system>Android\s\d+(\.\d+)?)/i', $str, $match, PREG_SET_ORDER);
    $matches['system'] = $match[0]['system'];
  } else if (preg_match('/Wind/', $str)) {
    // windows
    preg_match_all('/(?P<system>Windows\sNT\s\d+\.\d+)/i', $str, $match, PREG_SET_ORDER);
    if (strpos($match[0]['system'], '6.1')) {
      $matches['system'] = str_replace(' NT 6.1', ' 7', $match[0]['system']);
    } else if (strpos($match[0]['system'], '6.2')) {
      $matches['system'] = str_replace(' NT 6.2', ' 8', $match[0]['system']);
    } else if (strpos($match[0]['system'], '6.3')) {
      $matches['system'] = str_replace(' NT 6.3', ' 8.1', $match[0]['system']);
    } else if (strpos($match[0]['system'], '10.0')) {
      $matches['system'] = str_replace(' NT 10.0', ' 10', $match[0]['system']);
    } else if (strpos($match[0]['system'], '5.1')) {
      $matches['system'] = str_replace(' NT 5.1', ' XP', $match[0]['system']);
    }
  } else if (preg_match('/Linux/', $str)) {
    $matches['system'] = 'Linux';
    // 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.84 Safari/537.36'
  } else {
    $matches['system'] = 'Unknown';
  }

  // 判断浏览器信息
  if (preg_match('/QQBrowser/', $str)) {
    // QQ浏览器
    preg_match_all('/(?P<name>QQBrowser)\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = $match[0]['name'];
  } else if (preg_match('/MicroMessenger/', $str)) {
    // 微信内置浏览器
    preg_match_all('/(?P<name>MicroMessenger)\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = 'wechat';
  } else if (preg_match('/QQ\/\d/', $str)) {
    // QQ
    preg_match_all('/(?P<name>QQ)\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = $match[0]['name'];
  } else if (preg_match('/UCBrowser/', $str)) {
    // UC
    preg_match_all('/(?P<name>UCBrowser)\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = $match[0]['name'];
  } else if (preg_match('/Edge/', $str)) {
    // edge
    preg_match_all('/(?P<name>Edge)\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = $match[0]['name'];
  } else if (preg_match('/OPR/', $str)) {
    // opera
    preg_match_all('/(?P<name>OPR)\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = 'Opera';
  } else if (preg_match('/Chrome|MetaSr/', $str)) {
    // chrome
    preg_match_all('/(?P<name>(Chrome))\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = $match[0]['name'];
  } else if (preg_match('/Safari/', $str)) {
    // safari
    preg_match_all('/(?P<name>Version\/\d+\.\d+)/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = str_replace('Version/', '', $match[0]['name']);
    $matches['browserName'] = 'Safari';
  } else if (preg_match('/Firefox/', $str)) {
    // Firefox
    preg_match_all('/(?P<name>Firefox)\/(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = $match[0]['name'];
  } else if (preg_match('/Trident/', $str)) {
    // IE
    preg_match_all('/MSIE\s(?P<version>(\d+\.\d+))/i', $str, $match, PREG_SET_ORDER);
    $matches['browserVersion'] = $match[0]['version'];
    $matches['browserName'] = 'Internet-Explorer';
  } else {
    $matches['browserVersion'] = 'Unknown';
    $matches['browserName'] = 'Unknown';
  }
  return $matches;
}

// 访客等级
function get_author_class ($comment_author_email) {
  global $wpdb;
  $adminEmail = get_bloginfo('admin_email');
  $styleClass = get_option('xm_vue_options')['vip_style'];
  $author_count = count($wpdb -> get_results("SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email'"));
  if ($comment_author_email == $adminEmail) {
    return array('style' => $styleClass, 'level' => 'vip7', 'admin' => true, 'title' => '博主');
  } else {
    if ($author_count >= 1 && $author_count < 10) {
      return array('style' => $styleClass, 'level' => 'vip1', 'title' => 'LV.1');
    } else if ($author_count >= 10 && $author_count < 20) {
      return array('style' => $styleClass, 'level' => 'vip2', 'title' => 'LV.2');
    } else if ($author_count >= 20 && $author_count < 40) {
      return array('style' => $styleClass, 'level' => 'vip3', 'title' => 'LV.3');
    } else if ($author_count >= 40 && $author_count < 80) {
      return array('style' => $styleClass, 'level' => 'vip4', 'title' => 'LV.4');
    } else if ($author_count >= 80 && $author_count < 160) {
      return array('style' => $styleClass, 'level' => 'vip5', 'title' => 'LV.5');
    } else if ($author_count >= 160 && $author_count < 300) {
      return array('style' => $styleClass, 'level' => 'vip6', 'title' => 'LV.6');
    } else if ($author_count >= 300) {
      return array('style' => $styleClass, 'level' => 'vip7', 'title' => '博主好基友');
    }
  }
}
?>
