<?php
/**
 * 输出表情
 * @param string $str
 * @return array|string|string[]|null {}转换后的字符串
 */
function xm_output_smiley(string $str = "") {
    return preg_replace_callback("/(\[(?!img)\w+(\-[\x{4e00}-\x{9fa5}]+)?\])/u", function ($matchs) {
        global $wpsmiliestrans;
        $smilies_dir = get_option("xm_vue_options")["domain"] . '/images/smilies/';
        return '<img src="' . $smilies_dir . $wpsmiliestrans[$matchs[1]] . '" width="20" style="vertical-align:bottom;box-shadow:none;">';
    }, $str);
}

/**
 * 需要替换的地址
 * @param [type] $url 需要替换的地址，示例：https://www.xuanmo.xin/wp-json
 * @return array|string|string[]|null 返回替换后的地址，示例：/wp-json
 */
function replace_domain($url) {
    return preg_replace("/https?:\/\/(\w+\.)+\w+(:\d+)?/", "", $url);
}

/**
 * 生成用户评论头像
 * @param bool $isText
 * @param string $email
 * @return string|void
 */
function xm_generate_user_avatar(bool $isText, string $email) {
    global $avatar_colors;
    if ((bool)$isText) {
        preg_match("/\d/", md5($email), $matches);
        return $avatar_colors[$matches[0]];
    }
}

/**
 * 转换评论中的图片
 * @param $comment
 * @return array|string|string[]|null
 */
function xm_transform_comment_img($comment) {
    return preg_replace_callback("/\[img\]\s?((https?:\/\/)?(\/[\w\-]+)+\.\w+)\[\/img\]/", function ($matchs) {
        return "<img src='$matchs[1]' style='vertical-align: bottom; max-width: 40%; max-height: 250px;' />";
    }, $comment);
}

/** 格式化返回的数据
 * @param $obj
 * @return array
 */
function xm_format_comment_item($obj): array {
    global $xm_theme_options;
    return array(
        'id'           => $obj->comment_ID,
        'parentId'     => $obj->comment_parent,
        'postId'       => $obj->comment_post_ID,
        'authorName'   => $obj->comment_author,
        'authorSite'   => $obj->comment_author_url,
        'createTime'   => $obj->comment_date,
        'isApproved'   => (bool)$obj->comment_approved,
        'content'      => xm_output_smiley(xm_transform_comment_img($obj->comment_content)),
        'ua'           => $obj->comment_agent,
        'opinion'      => get_metadata('comment', $obj->comment_ID, 'opinion', true),
        'isTextAvatar' => (boolean)$xm_theme_options['text_pic'],
        'avatar'       => "https://gravatar.xuanmo.xin/avatar/" . md5(strtolower(trim($obj->comment_author_email))) . "?s=200",
        'avatarColor'  => xm_generate_user_avatar((boolean)$xm_theme_options['text_pic'], $obj->comment_author_email),
        'authorLevel'  => get_author_level($obj->comment_author_email)
    );
}
