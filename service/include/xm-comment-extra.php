<?php
// 访客等级
function get_author_level($comment_author_email) {
    global $wpdb;
    $adminEmail = get_bloginfo('admin_email');
    $styleClass = get_option('xm_vue_options')['vip_style'];
    $author_count = count($wpdb->get_results("SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email'"));
    if ($comment_author_email == $adminEmail) {
        return array('style' => $styleClass, 'level' => 'vip7', 'isAdmin' => true, 'title' => '博主');
    } else {
        if ($author_count >= 1 && $author_count < 10) {
            return array('style' => $styleClass, 'level' => 'vip1', 'title' => 'LV.1');
        } elseif ($author_count >= 10 && $author_count < 20) {
            return array('style' => $styleClass, 'level' => 'vip2', 'title' => 'LV.2');
        } elseif ($author_count >= 20 && $author_count < 40) {
            return array('style' => $styleClass, 'level' => 'vip3', 'title' => 'LV.3');
        } elseif ($author_count >= 40 && $author_count < 80) {
            return array('style' => $styleClass, 'level' => 'vip4', 'title' => 'LV.4');
        } elseif ($author_count >= 80 && $author_count < 160) {
            return array('style' => $styleClass, 'level' => 'vip5', 'title' => 'LV.5');
        } elseif ($author_count >= 160 && $author_count < 300) {
            return array('style' => $styleClass, 'level' => 'vip6', 'title' => 'LV.6');
        } elseif ($author_count >= 300) {
            return array('style' => $styleClass, 'level' => 'vip7', 'title' => '博主好基友');
        }
    }
}
