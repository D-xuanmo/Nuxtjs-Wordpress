<?php
// 自定义上传头像
require_once(TEMPLATEPATH . '/include/author-avatars.php');

// 主题扩展设置
require_once(TEMPLATEPATH . '/include/xm-theme-options.php');

// 添加自定义接口
require_once(TEMPLATEPATH . '/include/xm-api.php');
require_once(TEMPLATEPATH . '/include/v2/api.php');

require_once(TEMPLATEPATH . '/utils.php');

// 编辑器扩展功能
require_once(TEMPLATEPATH . '/include/insert-code.php');

// 邮件通知功能
require_once(TEMPLATEPATH . '/include/email_notify.php');

// 企业微信通知功能
require_once(TEMPLATEPATH . '/include/qywx_notify.php');

// Remove all default WP template redirects/lookups
remove_action('template_redirect', 'redirect_canonical');

// Redirect all requests to index.php so the Vue app is loaded and 404s aren't thrown
function remove_redirects() {
    add_rewrite_rule('^/(.+)/?', 'index.php', 'top');
}

add_action('init', 'remove_redirects');

// 移除后台左上角logo信息
function xm_admin_bar_remove() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}

add_action('wp_before_admin_bar_render', 'xm_admin_bar_remove', 0);

// 顶部添加自定义菜单
function toolbar_link_to_mypage($wp_admin_bar) {
    $wp_admin_bar->add_node(array(
        'id'    => 'my_page',
        'title' => '🎉查看站点',
        'href'  => get_option("xm_vue_options")["domain"],
        'meta'  => array(
            'target' => '_blank'
        )
    ));
    $wp_admin_bar->add_node(array(
        'id'    => 'instructions',
        'title' => '👉主题使用说明',
        'href'  => 'https://www.xuanmo.xin/details/2987',
        'meta'  => array(
            'target' => '_blank'
        )
    ));
    $wp_admin_bar->add_node(array(
        'id'    => 'issues',
        'title' => '👨‍💻‍意见反馈',
        'href'  => 'https://github.com/xuanmos/xm-nuxtjs-wordpress/issues',
        'meta'  => array(
            'target' => '_blank'
        )
    ));
}

add_action('admin_bar_menu', 'toolbar_link_to_mypage', 999);

/**
 * 删出查看站点等菜单
 */
function my_prefix_remove_admin_bar_item($wp_admin_bar) {
    $wp_admin_bar->remove_node('site-name');
}

add_action('admin_bar_menu', 'my_prefix_remove_admin_bar_item', 999);

/**
 * 关闭自动更新
 */
add_filter('automatic_updater_disabled', '__return_true');

/**
 * 注册菜单
 */
register_nav_menus();

/**
 * 添加特色头像
 */
add_theme_support('post-thumbnails');

/**
 * 禁止emoji表情
 */
function disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}

function disable_emojis_tinymce($plugins): array {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}

add_action('init', 'disable_emojis');

// 添加发布说说功能
function add_phrase() {
    $labels = array(
        'name'               => '说说',
        'singular_name'      => 'singularname',
        'add_new'            => '发表说说',
        'add_new_item'       => '发表说说',
        'edit_item'          => '编辑说说',
        'new_item'           => '新说说',
        'view_item'          => '查看说说',
        'search_items'       => '搜索说说',
        'not_found'          => '暂无说说',
        'not_found_in_trash' => '没有已遗弃的说说',
        'parent_item_colon'  => '',
        'menu_name'          => '说说'
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'description'        => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'excerpt')
    );
    register_post_type('phrase', $args);
}

add_action('init', 'add_phrase');

// 启用wordpress链接管理模块
add_filter('pre_option_link_manager_enabled', '__return_true');

/**
 * 设置摘要
 */
function xm_get_post_excerpt($length, $str) {
    $post_content = wp_strip_all_tags(get_post()->post_content, true);
    $post_excerpt = get_post()->post_excerpt;
    return (bool)get_option('xm_vue_options')['article_auto_summary'] || $post_excerpt == '' ? wp_trim_words($post_content, $length, $str) : $post_excerpt;
}

/*
 * 自定义登录页面的LOGO链接为首页链接,LOGO提示为网站名称
 */
add_filter('login_headerurl', function () {
    return get_bloginfo('url');
});
add_filter('login_headertext', function () {
    return get_bloginfo('name');
});

/*
 * 自定义登录页面的LOGO图片
 */
function my_custom_login_logo() {
    echo '
        <style>
        .login h1 a {
          background-image:url("' . get_option('xm_vue_options')['login_logo'] . '");
          border-radius: 50%;
        }
        ' . get_option('xm_vue_options')['login_css'] . '
        </style>
    ';
}

add_action('login_head', 'my_custom_login_logo');

/**
 * 给用户添加自定义字段
 */
function xm_user_contact($user_contactmethods) {
    unset($user_contactmethods['aim']);
    unset($user_contactmethods['yim']);
    unset($user_contactmethods['jabber']);
    $user_contactmethods['qq'] = 'QQ链接';
    $user_contactmethods['github_url'] = 'GitHub';
    $user_contactmethods['wechat_num'] = '微信号';
    $user_contactmethods['wechat_img'] = '微信二维码链接';
    $user_contactmethods['sina_url'] = '新浪微博';
    $user_contactmethods['sex'] = '性别';
    return $user_contactmethods;
}

add_filter('user_contactmethods', 'xm_user_contact');

/*
 * 解决php添加分号斜杠问题
 */
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value) {
        $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

/**
 * 允许未登录评论
 */
add_filter('rest_allow_anonymous_comments', '__return_true');

/*
 * 自定义表情路径和名称
 */
function xm_custom_smilies_src($img_src, $img) {
    return get_option("xm_vue_options")["domain"] . '/images/smilies/' . $img;
}

add_filter('smilies_src', 'xm_custom_smilies_src', 10, 2);

// 关闭自带表情
// add_filter('option_use_smilies', '__return_false');

function xm_custom_smilies_conversion() {
    global $wpsmiliestrans;
    if (!isset($wpsmiliestrans)) {
        $wpsmiliestrans = array(
            "[hashiqi]"        => "qq/hashiqi.png",
            "[huaji]"          => "qq/huaji.png",
            "[doge1]"          => "qq/doge.png",
            "[weixiao]"        => "qq/weixiao.gif",
            "[nanguo]"         => "qq/nanguo.gif",
            "[qiudale]"        => "qq/qiudale.gif",
            "[penxue]"         => "qq/penxue.gif",
            "[piezui]"         => "qq/piezui.gif",
            "[aoman]"          => "qq/aoman.gif",
            "[baiyan]"         => "qq/baiyan.gif",
            "[bishi]"          => "qq/bishi.gif",
            "[bizui]"          => "qq/bizui.gif",
            "[cahan]"          => "qq/cahan.gif",
            "[ciya]"           => "qq/ciya.gif",
            "[dabing]"         => "qq/dabing.gif",
            "[daku]"           => "qq/daku.gif",
            "[deyi]"           => "qq/deyi.gif",
            "[doge]"           => "qq/doge.gif",
            "[fadai]"          => "qq/fadai.gif",
            "[fanu]"           => "qq/fanu.gif",
            "[fendou]"         => "qq/fendou.gif",
            "[ganga]"          => "qq/ganga.gif",
            "[guzhang]"        => "qq/guzhang.gif",
            "[haixiu]"         => "qq/haixiu.gif",
            "[hanxiao]"        => "qq/hanxiao.gif",
            "[haqian]"         => "qq/haqian.gif",
            "[huaixiao]"       => "qq/huaixiao.gif",
            "[jie]"            => "qq/jie.gif",
            "[jingkong]"       => "qq/jingkong.gif",
            "[jingxi]"         => "qq/jingxi.gif",
            "[jingya]"         => "qq/jingya.gif",
            "[keai]"           => "qq/keai.gif",
            "[kelian]"         => "qq/kelian.gif",
            "[koubi]"          => "qq/koubi.gif",
            "[ku]"             => "qq/ku.gif",
            "[kuaikule]"       => "qq/kuaikule.gif",
            "[kulou]"          => "qq/kulou.gif",
            "[kun]"            => "qq/kun.gif",
            "[leiben]"         => "qq/leiben.gif",
            "[lenghan]"        => "qq/lenghan.gif",
            "[liuhan]"         => "qq/liuhan.gif",
            "[liulei]"         => "qq/liulei.gif",
            "[qiaoda]"         => "qq/qiaoda.gif",
            "[qinqin]"         => "qq/qinqin.gif",
            "[saorao]"         => "qq/saorao.gif",
            "[se]"             => "qq/se.gif",
            "[shuai]"          => "qq/shuai.gif",
            "[shui]"           => "qq/shui.gif",
            "[tiaopi]"         => "qq/tiaopi.gif",
            "[touxiao]"        => "qq/touxiao.gif",
            "[tu]"             => "qq/tu.gif",
            "[tuosai]"         => "qq/tuosai.gif",
            "[weiqu]"          => "qq/weiqu.gif",
            "[wozuimei]"       => "qq/wozuimei.gif",
            "[wunai]"          => "qq/wunai.gif",
            "[xia]"            => "qq/xia.gif",
            "[xiaojiujie]"     => "qq/xiaojiujie.gif",
            "[xiaoku]"         => "qq/xiaoku.gif",
            "[xieyanxiao]"     => "qq/xieyanxiao.gif",
            "[xu]"             => "qq/xu.gif",
            "[yinxian]"        => "qq/yinxian.gif",
            "[yiwen]"          => "qq/yiwen.gif",
            "[zuohengheng]"    => "qq/zuohengheng.gif",
            "[youhengheng]"    => "qq/youhengheng.gif",
            "[yun]"            => "qq/yun.gif",
            "[zaijian]"        => "qq/zaijian.gif",
            "[zhayanjian]"     => "qq/zhayanjian.gif",
            "[zhemo]"          => "qq/zhemo.gif",
            "[zhouma]"         => "qq/zhouma.gif",
            "[zhuakuang]"      => "qq/zhuakuang.gif",
            "[aini]"           => "qq/aini.gif",
            "[baoquan]"        => "qq/baoquan.gif",
            "[gouyin]"         => "qq/gouyin.gif",
            "[qiang]"          => "qq/qiang.gif",
            "[OK]"             => "qq/OK.gif",
            "[woshou]"         => "qq/woshou.gif",
            "[quantou]"        => "qq/quantou.gif",
            "[shengli]"        => "qq/shengli.gif",
            "[aixin]"          => "qq/aixin.gif",
            "[bangbangtang]"   => "qq/bangbangtang.gif",
            "[baojin]"         => "qq/baojin.gif",
            "[caidao]"         => "qq/caidao.gif",
            "[lanqiu]"         => "qq/lanqiu.gif",
            "[chi]"            => "qq/chi.gif",
            "[dan]"            => "qq/dan.gif",
            "[haobang]"        => "qq/haobang.gif",
            "[hecai]"          => "qq/hecai.gif",
            "[hexie]"          => "qq/hexie.gif",
            "[juhua]"          => "qq/juhua.gif",
            "[pijiu]"          => "qq/pijiu.gif",
            "[shouqiang]"      => "qq/shouqiang.gif",
            "[xiaoyanger]"     => "qq/xiaoyanger.gif",
            "[xigua]"          => "qq/xigua.gif",
            "[yangtuo]"        => "qq/yangtuo.gif",
            "[youling]"        => "qq/youling.gif",
            "[jiayoubisheng]"  => "qq/jiayoubisheng.gif",
            "[jiayoubaobao]"   => "qq/jiayoubaobao.gif",
            "[kouzhaohuti]"    => "qq/kouzhaohuti.gif",
            "[banzhuanzhong]"  => "qq/banzhuanzhong.gif",
            "[mangdaoqifei]"   => "qq/mangdaoqifei.gif",
            "[naokuoteng]"     => "qq/naokuoteng.gif",
            "[cangsang]"       => "qq/cangsang.gif",
            "[wulian]"         => "qq/wulian.gif",
            "[layanjing]"      => "qq/layanjing.gif",
            "[oyo]"            => "qq/oyo.gif",
            "[toutu]"          => "qq/toutu.gif",
            "[wenhaolian]"     => "qq/wenhaolian.gif",
            "[anzhongguancha]" => "qq/anzhongguancha.gif",
            "[emm]"            => "qq/emm.gif",
            "[chigua]"         => "qq/chigua.gif",
            "[heheda]"         => "qq/heheda.gif",
            "[wangwang]"       => "qq/wangwang.png",
            "[wosuanliao]"     => "qq/wosuanliao.png",
            "[色]"              => 'icon_razz.gif',
            "[难过]"             => 'icon_sad.gif',
            "[闭嘴]"             => 'icon_evil.gif',
            "[吐舌头]"            => 'icon_exclaim.gif',
            "[微笑]"             => 'icon_smile.gif',
            "[可爱]"             => 'icon_redface.gif',
            '[kiss]'           => 'icon_biggrin.gif',
            "[惊讶]"             => 'icon_surprised.gif',
            "[饥饿]"             => 'icon_eek.gif',
            "[晕]"              => 'icon_confused.gif',
            "[酷]"              => 'icon_cool.gif',
            "[坏笑]"             => 'icon_lol.gif',
            "[发怒]"             => 'icon_mad.gif',
            "[憨笑]"             => 'icon_twisted.gif',
            "[萌萌哒]"            => 'icon_rolleyes.gif',
            "[吃东西]"            => 'icon_wink.gif',
            "[色咪咪]"            => 'icon_idea.gif',
            "[囧]"              => 'icon_arrow.gif',
            "[害羞]"             => 'icon_neutral.gif',
            "[流泪]"             => 'icon_cry.gif',
            "[流汗]"             => 'icon_question.gif',
            "[你懂的]"            => 'icon_mrgreen.gif',
            "[pp-黑线]"          => "paopao/黑线.png",
            "[pp-香蕉]"          => "paopao/香蕉.png",
            "[pp-音乐]"          => "paopao/音乐.png",
            "[pp-阴险]"          => "paopao/阴险.png",
            "[pp-钱币]"          => "paopao/钱币.png",
            "[pp-酸爽]"          => "paopao/酸爽.png",
            "[pp-酷]"           => "paopao/酷.png",
            "[pp-鄙视]"          => "paopao/鄙视.png",
            "[pp-蜡烛]"          => "paopao/蜡烛.png",
            "[pp-蛋糕]"          => "paopao/蛋糕.png",
            "[pp-药丸]"          => "paopao/药丸.png",
            "[pp-花心]"          => "paopao/花心.png",
            "[pp-胜利]"          => "paopao/胜利.png",
            "[pp-红领巾]"         => "paopao/红领巾.png",
            "[pp-笑眼]"          => "paopao/笑眼.png",
            "[pp-笑尿]"          => "paopao/笑尿.png",
            "[pp-礼物]"          => "paopao/礼物.png",
            "[pp-睡觉]"          => "paopao/睡觉.png",
            "[pp-真棒]"          => "paopao/真棒.png",
            "[pp-疑问]"          => "paopao/疑问.png",
            "[pp-玫瑰]"          => "paopao/玫瑰.png",
            "[pp-狂汗]"          => "paopao/狂汗.png",
            "[pp-犀利]"          => "paopao/犀利.png",
            "[pp-爱心]"          => "paopao/爱心.png",
            "[pp-灯泡]"          => "paopao/灯泡.png",
            "[pp-滑稽]"          => "paopao/滑稽.png",
            "[pp-泪]"           => "paopao/泪.png",
            "[pp-沙发]"          => "paopao/沙发.png",
            "[pp-汗]"           => "paopao/汗.png",
            "[pp-星星月亮]"        => "paopao/星星月亮.png",
            "[pp-捂嘴笑]"         => "paopao/捂嘴笑.png",
            "[pp-挖鼻]"          => "paopao/挖鼻.png",
            "[pp-手纸]"          => "paopao/手纸.png",
            "[pp-懒得理]"         => "paopao/懒得理.png",
            "[pp-惊讶]"          => "paopao/惊讶.png",
            "[pp-惊哭]"          => "paopao/惊哭.png",
            "[pp-怒]"           => "paopao/怒.png",
            "[pp-心碎]"          => "paopao/心碎.png",
            "[pp-彩虹]"          => "paopao/彩虹.png",
            "[pp-小红脸]"         => "paopao/小红脸.png",
            "[pp-小乖]"          => "paopao/小乖.png",
            "[pp-委屈]"          => "paopao/委屈.png",
            "[pp-太阳]"          => "paopao/太阳.png",
            "[pp-太开心]"         => "paopao/太开心.png",
            "[pp-大拇指]"         => "paopao/大拇指.png",
            "[pp-喷]"           => "paopao/喷.png",
            "[pp-啊]"           => "paopao/啊.png",
            "[pp-哈哈]"          => "paopao/哈哈.png",
            "[pp-咖啡]"          => "paopao/咖啡.png",
            "[pp-呵呵]"          => "paopao/呵呵.png",
            "[pp-呀咩爹]"         => "paopao/呀咩爹.png",
            "[pp-吐舌]"          => "paopao/吐舌.png",
            "[pp-吐]"           => "paopao/吐.png",
            "[pp-勉强]"          => "paopao/勉强.png",
            "[pp-便便]"          => "paopao/便便.png",
            "[pp-你懂的]"         => "paopao/你懂的.png",
            "[pp-乖]"           => "paopao/乖.png",
            "[pp-不高兴]"         => "paopao/不高兴.png",
            "[pp-what]"        => "paopao/what.png",
            "[pp-OK]"          => "paopao/OK.png",
            "[pp-haha]"        => "paopao/haha.png"
        );
    }
}

add_action('init', 'xm_custom_smilies_conversion', 3);

/*
 * 评论区@功能
 */
function comment_add_at($comment_text, $comment = '') {
    if ($comment->comment_parent > 0) {
        $comment_text = '@<a href="#comment-' . $comment->comment_parent . '" class="c-theme">' . get_comment_author($comment->comment_parent) . '</a> ' . $comment_text;
    }
    return $comment_text;
}

add_filter('comment_text', 'comment_add_at', 20, 2);

/**
 * 非管理员上传图片
 */
function comments_embed_img($comment) {
    return preg_replace('/(\[img\]\s*(\S+)\s*\[\/img\])+/', '<img src="$2" style="vertical-align: bottom; max-width: 40%; max-height: 250px;" />', $comment);
}

add_action('comment_text', 'comments_embed_img');

// 添加svg文件上传
function xm_upload_mimes($mimes = array()) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'xm_upload_mimes');

//新建或更新文章时移除 noreferrer
function xm_targeted_link_rel_remove_noreferrer($rel_values) {
    return preg_replace('/noreferrer\s*/i', '', $rel_values);
}

add_filter('wp_targeted_link_rel', 'xm_targeted_link_rel_remove_noreferrer', 999);

//新建或更新文章时移除 noopener
function xm_targeted_link_rel_remove_noopener($rel_values) {
    return preg_replace('/noopener\s*/i', '', $rel_values);
}

add_filter('wp_targeted_link_rel', 'xm_targeted_link_rel_remove_noopener', 999);

function custom_preview_link() {
    return get_option("xm_vue_options")["domain"] . "/detail/" . get_the_ID() . "?preview=true";
}

add_filter('preview_post_link', 'custom_preview_link');