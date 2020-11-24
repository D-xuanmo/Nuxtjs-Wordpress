<?php
/**
 * 自定义上传头像
 */
require_once(TEMPLATEPATH . '/include/author-avatars.php');

/**
 * 主题扩展设置
 */
require_once(TEMPLATEPATH . '/include/xm-theme-options.php');

/**
 * 添加自定义接口
 */
require_once(TEMPLATEPATH . '/include/xm-api.php');

require_once(TEMPLATEPATH . '/utils.php');

// Remove all default WP template redirects/lookups
remove_action('template_redirect', 'redirect_canonical');

// Redirect all requests to index.php so the Vue app is loaded and 404s aren't thrown
function remove_redirects()
{
    add_rewrite_rule('^/(.+)/?', 'index.php', 'top');
}

add_action('init', 'remove_redirects');

// 移除后台左上角logo信息
function xm_admin_bar_remove()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'xm_admin_bar_remove', 0);

// 顶部添加自定义菜单
function toolbar_link_to_mypage($wp_admin_bar)
{
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
function my_prefix_remove_admin_bar_item($wp_admin_bar)
{
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
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
add_action('init', 'disable_emojis');

// 添加发布说说功能
function add_phrase()
{
    $labels = array(
        'name' => '说说',
        'singular_name' => 'singularname',
        'add_new' => '发表说说',
        'add_new_item' => '发表说说',
        'edit_item' => '编辑说说',
        'new_item' => '新说说',
        'view_item' => '查看说说',
        'search_items' => '搜索说说',
        'not_found' => '暂无说说',
        'not_found_in_trash' => '没有已遗弃的说说',
        'parent_item_colon' => '',
        'menu_name' => '说说'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'description' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor','author', 'excerpt')
    );
    register_post_type('phrase', $args);
}
add_action('init', 'add_phrase');

// 启用wordpress链接管理模块
add_filter('pre_option_link_manager_enabled', '__return_true');

/**
 * 设置摘要
 */
function xm_get_post_excerpt($length, $str)
{
    $post_content = wp_strip_all_tags(get_post()->post_content, true);
    $post_excerpt = get_post()->post_excerpt;
    return (bool) get_option('xm_vue_options')['article_auto_summary'] || $post_excerpt == '' ? wp_trim_words($post_content, $length, $str) : $post_excerpt;
}

/*
 * 自定义登录页面的LOGO链接为首页链接,LOGO提示为网站名称
 */
add_filter('login_headerurl', create_function(false, "return get_bloginfo('url');"));
add_filter('login_headertitle', create_function(false, "return get_bloginfo('name');"));

/*
 * 自定义登录页面的LOGO图片
 */
function my_custom_login_logo()
{
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
function xm_user_contact($user_contactmethods)
{
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
    function stripslashes_deep($value)
    {
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
 * 添加自定义编辑器按钮
 */
function add_my_media_button()
{
    echo '<a href="javascript:;" id="html-transform" class="button">html尖括号转义</a>';
}

function appthemes_add_quicktags()
{
    ?>
    <script>
        if (typeof QTags !== 'undefined') {
            var aLanguage = ['html', 'css', 'sass', 'scss', 'less', 'javascript', 'php', 'json', 'http', 'nginx'];
            for (var i = 0, length = aLanguage.length; i < length; i++) {
                QTags.addButton(aLanguage[i], aLanguage[i], '\n<pre class="language-' + aLanguage[i] + ' line-numbers"><code class="language-' + aLanguage[i] + '">\n', '\n</code></pre>\n');
            }
            QTags.addButton('h2', 'h2', '<h2>', '</h2>');
            QTags.addButton('2-text', '2-text', '<span style="display:inline-block; width:28px;">', '</span>');
            QTags.addButton('star', 'star', '<i class="iconfont icon-star c-theme">', '</i>');
            QTags.addButton('arrow-right', 'arrow-right', '<i class="iconfont icon-arrow-right-f">', '</i>');
        }

        // 添加html转换容器
        jQuery(function () {
            jQuery('#html-transform').click(function () {
                jQuery('body').append(
                    '<div id="xm-transform">'
                    + '<textarea name="name" rows="15" cols="100"></textarea>'
                    + '<span id="xm-transfom-btn">转换</span>'
                    + '<span id="xm-copy-btn">复制</span>'
                    + '</div>'
                );
                jQuery('#xm-transform')
                    .css({
                        position: 'fixed',
                        top: 0,
                        left: 0,
                        zIndex: 99999,
                        width: '100%',
                        height: '100%',
                        background: 'rgba(255,255,255,0.7)'
                    })
                    .children('textarea').css({
                        resize: 'none',
                        position: 'absolute',
                        top: '50%',
                        left: '50%',
                        width: '60%',
                        height: '300px',
                        transform: 'translate(-50%, -50%)'
                    })
                    .siblings('span').css({
                        position: 'absolute',
                        top: '90%',
                        left: '50%',
                        width: '100px',
                        height: '40px',
                        borderRadius: '5px',
                        background: '#2196F3',
                        textAlign: 'center',
                        lineHeight: '40px',
                        color: '#fff',
                        cursor: 'pointer'
                    });
                jQuery('textarea').click(function (e) {
                    e.stopPropagation();
                });
                jQuery('#xm-transfom-btn')
                    .css('transform', 'translateX(-115%)')
                    .click(function (e) {
                        e.stopPropagation();
                        jQuery(this).siblings('textarea').val(function () {
                            return jQuery(this).val().replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        });
                    });
                jQuery('#xm-copy-btn').click(function (e) {
                    e.stopPropagation();
                    jQuery(this).siblings('textarea')[0].select();
                    if (document.execCommand('Copy')) {
                        jQuery(this).text('复制成功');
                    }
                });
                jQuery('#xm-transform').click(function () {
                    jQuery(this).remove();
                });
            });
        });
    </script>
  <?php
}

add_action('media_buttons', 'add_my_media_button');
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');

/*
 * 自定义表情路径和名称
 */
function xm_custom_smilies_src($img_src, $img)
{
    return get_option("xm_vue_options")["domain"] . '/images/smilies/' . $img;
}
add_filter('smilies_src', 'xm_custom_smilies_src', 10, 2);

// 关闭自带表情
// add_filter('option_use_smilies', '__return_false');

function xm_custom_smilies_conversion()
{
    global $wpsmiliestrans;
    if (!isset($wpsmiliestrans)) {
        $wpsmiliestrans = array(
        "[hashiqi]"      => "qq/hashiqi.png",
        "[huaji]"        => "qq/huaji.png",
        "[doge1]"        => "qq/doge.png",
        "[weixiao]"      => "qq/weixiao.gif",
        "[nanguo]"       => "qq/nanguo.gif",
        "[qiudale]"      => "qq/qiudale.gif",
        "[penxue]"       => "qq/penxue.gif",
        "[piezui]"       => "qq/piezui.gif",
        "[aoman]"        => "qq/aoman.gif",
        "[baiyan]"       => "qq/baiyan.gif",
        "[bishi]"        => "qq/bishi.gif",
        "[bizui]"        => "qq/bizui.gif",
        "[cahan]"        => "qq/cahan.gif",
        "[ciya]"         => "qq/ciya.gif",
        "[dabing]"       => "qq/dabing.gif",
        "[daku]"         => "qq/daku.gif",
        "[deyi]"         => "qq/deyi.gif",
        "[doge]"         => "qq/doge.gif",
        "[fadai]"        => "qq/fadai.gif",
        "[fanu]"         => "qq/fanu.gif",
        "[fendou]"       => "qq/fendou.gif",
        "[ganga]"        => "qq/ganga.gif",
        "[guzhang]"      => "qq/guzhang.gif",
        "[haixiu]"       => "qq/haixiu.gif",
        "[hanxiao]"      => "qq/hanxiao.gif",
        "[haqian]"       => "qq/haqian.gif",
        "[huaixiao]"     => "qq/huaixiao.gif",
        "[jie]"          => "qq/jie.gif",
        "[jingkong]"     => "qq/jingkong.gif",
        "[jingxi]"       => "qq/jingxi.gif",
        "[jingya]"       => "qq/jingya.gif",
        "[keai]"         => "qq/keai.gif",
        "[kelian]"       => "qq/kelian.gif",
        "[koubi]"        => "qq/koubi.gif",
        "[ku]"           => "qq/ku.gif",
        "[kuaikule]"     => "qq/kuaikule.gif",
        "[kulou]"        => "qq/kulou.gif",
        "[kun]"          => "qq/kun.gif",
        "[leiben]"       => "qq/leiben.gif",
        "[lenghan]"      => "qq/lenghan.gif",
        "[liuhan]"       => "qq/liuhan.gif",
        "[liulei]"       => "qq/liulei.gif",
        "[qiaoda]"       => "qq/qiaoda.gif",
        "[qinqin]"       => "qq/qinqin.gif",
        "[saorao]"       => "qq/saorao.gif",
        "[se]"           => "qq/se.gif",
        "[shuai]"        => "qq/shuai.gif",
        "[shui]"         => "qq/shui.gif",
        "[tiaopi]"       => "qq/tiaopi.gif",
        "[touxiao]"      => "qq/touxiao.gif",
        "[tu]"           => "qq/tu.gif",
        "[tuosai]"       => "qq/tuosai.gif",
        "[weiqu]"        => "qq/weiqu.gif",
        "[wozuimei]"     => "qq/wozuimei.gif",
        "[wunai]"        => "qq/wunai.gif",
        "[xia]"          => "qq/xia.gif",
        "[xiaojiujie]"   => "qq/xiaojiujie.gif",
        "[xiaoku]"       => "qq/xiaoku.gif",
        "[xieyanxiao]"   => "qq/xieyanxiao.gif",
        "[xu]"           => "qq/xu.gif",
        "[yinxian]"      => "qq/yinxian.gif",
        "[yiwen]"        => "qq/yiwen.gif",
        "[zuohengheng]"  => "qq/zuohengheng.gif",
        "[youhengheng]"  => "qq/youhengheng.gif",
        "[yun]"          => "qq/yun.gif",
        "[zaijian]"      => "qq/zaijian.gif",
        "[zhayanjian]"   => "qq/zhayanjian.gif",
        "[zhemo]"        => "qq/zhemo.gif",
        "[zhouma]"       => "qq/zhouma.gif",
        "[zhuakuang]"    => "qq/zhuakuang.gif",
        "[aini]"         => "qq/aini.gif",
        "[baoquan]"      => "qq/baoquan.gif",
        "[gouyin]"       => "qq/gouyin.gif",
        "[qiang]"        => "qq/qiang.gif",
        "[OK]"           => "qq/OK.gif",
        "[woshou]"       => "qq/woshou.gif",
        "[quantou]"      => "qq/quantou.gif",
        "[shengli]"      => "qq/shengli.gif",
        "[aixin]"        => "qq/aixin.gif",
        "[bangbangtang]" => "qq/bangbangtang.gif",
        "[baojin]"       => "qq/baojin.gif",
        "[caidao]"       => "qq/caidao.gif",
        "[lanqiu]"       => "qq/lanqiu.gif",
        "[chi]"          => "qq/chi.gif",
        "[dan]"          => "qq/dan.gif",
        "[haobang]"      => "qq/haobang.gif",
        "[hecai]"        => "qq/hecai.gif",
        "[hexie]"        => "qq/hexie.gif",
        "[juhua]"        => "qq/juhua.gif",
        "[pijiu]"        => "qq/pijiu.gif",
        "[shouqiang]"    => "qq/shouqiang.gif",
        "[xiaoyanger]"   => "qq/xiaoyanger.gif",
        "[xigua]"        => "qq/xigua.gif",
        "[yangtuo]"      => "qq/yangtuo.gif",
        "[youling]"      => "qq/youling.gif",
        "[jiayoubisheng]"      => "qq/jiayoubisheng.gif",
        "[jiayoubaobao]"      => "qq/jiayoubaobao.gif",
        "[kouzhaohuti]"      => "qq/kouzhaohuti.gif",
        "[banzhuanzhong]"      => "qq/banzhuanzhong.gif",
        "[mangdaoqifei]"      => "qq/mangdaoqifei.gif",
        "[naokuoteng]"      => "qq/naokuoteng.gif",
        "[cangsang]"      => "qq/cangsang.gif",
        "[wulian]"      => "qq/wulian.gif",
        "[layanjing]"      => "qq/layanjing.gif",
        "[oyo]"      => "qq/oyo.gif",
        "[toutu]"      => "qq/toutu.gif",
        "[wenhaolian]"      => "qq/wenhaolian.gif",
        "[anzhongguancha]"      => "qq/anzhongguancha.gif",
        "[emm]"      => "qq/emm.gif",
        "[chigua]"      => "qq/chigua.gif",
        "[heheda]"      => "qq/heheda.gif",
        "[wangwang]"      => "qq/wangwang.png",
        "[wosuanliao]"      => "qq/wosuanliao.png",
        "[色]"            => 'icon_razz.gif',
        "[难过]"           => 'icon_sad.gif',
        "[闭嘴]"           => 'icon_evil.gif',
        "[吐舌头]"          => 'icon_exclaim.gif',
        "[微笑]"           => 'icon_smile.gif',
        "[可爱]"           => 'icon_redface.gif',
        '[kiss]'         => 'icon_biggrin.gif',
        "[惊讶]"           => 'icon_surprised.gif',
        "[饥饿]"           => 'icon_eek.gif',
        "[晕]"            => 'icon_confused.gif',
        "[酷]"            => 'icon_cool.gif',
        "[坏笑]"           => 'icon_lol.gif',
        "[发怒]"           => 'icon_mad.gif',
        "[憨笑]"           => 'icon_twisted.gif',
        "[萌萌哒]"          => 'icon_rolleyes.gif',
        "[吃东西]"          => 'icon_wink.gif',
        "[色咪咪]"          => 'icon_idea.gif',
        "[囧]"            => 'icon_arrow.gif',
        "[害羞]"           => 'icon_neutral.gif',
        "[流泪]"           => 'icon_cry.gif',
        "[流汗]"           => 'icon_question.gif',
        "[你懂的]"          => 'icon_mrgreen.gif',
        "[pp-黑线]"        => "paopao/黑线.png",
        "[pp-香蕉]"        => "paopao/香蕉.png",
        "[pp-音乐]"        => "paopao/音乐.png",
        "[pp-阴险]"        => "paopao/阴险.png",
        "[pp-钱币]"        => "paopao/钱币.png",
        "[pp-酸爽]"        => "paopao/酸爽.png",
        "[pp-酷]"         => "paopao/酷.png",
        "[pp-鄙视]"        => "paopao/鄙视.png",
        "[pp-蜡烛]"        => "paopao/蜡烛.png",
        "[pp-蛋糕]"        => "paopao/蛋糕.png",
        "[pp-药丸]"        => "paopao/药丸.png",
        "[pp-花心]"        => "paopao/花心.png",
        "[pp-胜利]"        => "paopao/胜利.png",
        "[pp-红领巾]"       => "paopao/红领巾.png",
        "[pp-笑眼]"        => "paopao/笑眼.png",
        "[pp-笑尿]"        => "paopao/笑尿.png",
        "[pp-礼物]"        => "paopao/礼物.png",
        "[pp-睡觉]"        => "paopao/睡觉.png",
        "[pp-真棒]"        => "paopao/真棒.png",
        "[pp-疑问]"        => "paopao/疑问.png",
        "[pp-玫瑰]"        => "paopao/玫瑰.png",
        "[pp-狂汗]"        => "paopao/狂汗.png",
        "[pp-犀利]"        => "paopao/犀利.png",
        "[pp-爱心]"        => "paopao/爱心.png",
        "[pp-灯泡]"        => "paopao/灯泡.png",
        "[pp-滑稽]"        => "paopao/滑稽.png",
        "[pp-泪]"         => "paopao/泪.png",
        "[pp-沙发]"        => "paopao/沙发.png",
        "[pp-汗]"         => "paopao/汗.png",
        "[pp-星星月亮]"      => "paopao/星星月亮.png",
        "[pp-捂嘴笑]"       => "paopao/捂嘴笑.png",
        "[pp-挖鼻]"        => "paopao/挖鼻.png",
        "[pp-手纸]"        => "paopao/手纸.png",
        "[pp-懒得理]"       => "paopao/懒得理.png",
        "[pp-惊讶]"        => "paopao/惊讶.png",
        "[pp-惊哭]"        => "paopao/惊哭.png",
        "[pp-怒]"         => "paopao/怒.png",
        "[pp-心碎]"        => "paopao/心碎.png",
        "[pp-彩虹]"        => "paopao/彩虹.png",
        "[pp-小红脸]"       => "paopao/小红脸.png",
        "[pp-小乖]"        => "paopao/小乖.png",
        "[pp-委屈]"        => "paopao/委屈.png",
        "[pp-太阳]"        => "paopao/太阳.png",
        "[pp-太开心]"       => "paopao/太开心.png",
        "[pp-大拇指]"       => "paopao/大拇指.png",
        "[pp-喷]"         => "paopao/喷.png",
        "[pp-啊]"         => "paopao/啊.png",
        "[pp-哈哈]"        => "paopao/哈哈.png",
        "[pp-咖啡]"        => "paopao/咖啡.png",
        "[pp-呵呵]"        => "paopao/呵呵.png",
        "[pp-呀咩爹]"       => "paopao/呀咩爹.png",
        "[pp-吐舌]"        => "paopao/吐舌.png",
        "[pp-吐]"         => "paopao/吐.png",
        "[pp-勉强]"        => "paopao/勉强.png",
        "[pp-便便]"        => "paopao/便便.png",
        "[pp-你懂的]"       => "paopao/你懂的.png",
        "[pp-乖]"         => "paopao/乖.png",
        "[pp-不高兴]"       => "paopao/不高兴.png",
        "[pp-what]"      => "paopao/what.png",
        "[pp-OK]"        => "paopao/OK.png",
        "[pp-haha]"      => "paopao/haha.png"
    );
    }
}
add_action('init', 'xm_custom_smilies_conversion', 3);

/*
 * 评论区@功能
 */
function comment_add_at($comment_text, $comment = '')
{
    if ($comment->comment_parent > 0) {
        $comment_text = '@<a href="#comment-' . $comment->comment_parent . '" class="c-theme">' . get_comment_author($comment->comment_parent) . '</a> ' . $comment_text;
    }
    return $comment_text;
}
add_filter('comment_text', 'comment_add_at', 20, 2);

/**
 * 非管理员上传图片
 */
function comments_embed_img($comment)
{
    $comment = preg_replace('/(\[img\]\s*(\S+)\s*\[\/img\])+/', '<img src="$2" style="vertical-align: bottom; max-width: 40%; max-height: 250px;" />', $comment);
    return $comment;
}
add_action('comment_text', 'comments_embed_img');

/**
 * 邮件回复
 */
function ludou_comment_mail_notify($comment_id, $comment_status)
{
    // 评论必须经过审核才会发送通知邮件
    if ($comment_status !== 'approve' && $comment_status !== 1) {
        return;
    }
    $comment = get_comment($comment_id);
    if ($comment->comment_parent != '0') {
        $parent_comment = get_comment($comment->comment_parent);
        // 邮件接收者email
        $to = trim($parent_comment->comment_author_email);

        // 邮件标题
        $subject = '您在[' . get_option("blogname") . ']的留言有了新的回复!';

        // 页面类型
        $pageType = '';
        if (get_post($comment->comment_post_ID)->post_type === 'post') {
            $pageType = 'details';
        } elseif (get_post($comment->comment_post_ID)->post_type === 'page') {
            $pageType = 'page';
        }

        // 邮件内容，自行修改，支持HTML
        $message = '
            <style>
            #container {
              width: 90%;
              margin: 20px auto;
              border: 1px solid #e9eaed;
              border-radius: 10px;
              box-shadow: 0 0 10px rgba(0,0,0,.2);
              overflow: hidden;
            }
            #container * {
                font-size: PingFangSC-Regular,Microsoft Yahei;
            }
            #container a {
              color: #ffffff;
            }
            .header {
              height: 60px;
              padding: 0 15px;
              background: #ff7a8a;
              background: -webkit-linear-gradient(to right, #00dbde, #ff7a8a);
              background: linear-gradient(to right, #00dbde, #ff7a8a);
              color: #ffffff;
              line-height: 60px;
            }
            .comment-content {
              padding: 15px;
              background-color: #f9f6f2;
              border-radius: 5px;
            }
          </style>
          <div id="container">
            <div class="header">您在 <a href="' . get_option('xm_vue_options')['domain'] . '">' . get_option('blogname') . ' </a> 的留言有新回复啦！</div>
            <div style="width:90%; margin:0 auto">
              <p><strong>' . $parent_comment->comment_author . '</strong> 您好!</p>

              <p>您在 [' . get_option('blogname') . '] 的文章<strong>《' . get_the_title($comment->comment_post_ID) . '》</strong>上发表的评论有新回复啦，快来看看吧 ^_^:</p>

              <p>这是您的评论:</p>

              <div class="comment-content">' . xm_output_smiley($parent_comment->comment_content) . '</div>

              <p><strong>' . $comment->comment_author . '</strong> 给您的回复是:</p>

              <div class="comment-content">' . xm_output_smiley($comment->comment_content) . '</div>

              <p>您也可移步到文章<a style="text-decoration:none; color:#1890ff" href="' . get_option('xm_vue_options')['domain'] . '/' . $pageType . '/' . $comment->comment_post_ID . '"> 《' . get_the_title($comment->comment_post_ID) . '》 </a>查看完整回复内容</p>

              <p style="padding-bottom: 10px; border-bottom: 1px dashed #ccc;">欢迎再次光临 <a style="text-decoration:none; color:#1890ff" href="' . get_option('xm_vue_options')['domain'] . '">' . get_option('blogname') . '</a></p>

              <p style="font-size: 12px;color: #f00;">(注：此邮件由系统自动发出, 请勿回复。)</p>
            </div>
          </div>
        ';
        $message_headers = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
        // 不用给不填email的评论者和管理员发提醒邮件
        if ($to != '' && $to != get_bloginfo('admin_email')) {
            wp_mail($to, $subject, $message, $message_headers);
        }
    }
}
// 编辑和管理员的回复直接发送提醒邮件，因为编辑和管理员的评论不需要审核
add_action('comment_post', 'ludou_comment_mail_notify', 20, 2);
// 普通访客发表的评论，等博主审核后再发送提醒邮件
add_action('wp_set_comment_status', 'ludou_comment_mail_notify', 20, 2);

// 修改发件人名字为博客名字
function xm_new_from_name($email)
{
    return get_option('blogname');
}
add_filter('wp_mail_from_name', 'xm_new_from_name');

// 有人评论时通知管理员
function xm_new_comment($comment_id)
{
    $to = get_bloginfo('admin_email');
    $comment = get_comment($comment_id);
    $title = '['. get_option('blogname') .'] 新评论："'. get_the_title($comment->comment_post_ID) .'"';
    $message = '
        <style>
        #container {
          width: 90%;
          margin: 20px auto;
          border: 1px solid #e9eaed;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0,0,0,.2);
          overflow: hidden;
        }
        #container * {
            font-size: PingFangSC-Regular,Microsoft Yahei;
        }
        .header {
          height: 60px;
          padding: 0 15px;
          background: #ff7a8a;
          background: -webkit-linear-gradient(to right, #00dbde, #ff7a8a);
          background: linear-gradient(to right, #00dbde, #ff7a8a);
          color: #ffffff;
          line-height: 60px;
        }
        .header a {
          color: #ffffff;
        }
        .comment-content {
          padding: 15px;
          background-color: #f9f6f2;
          border-radius: 5px;
        }
      </style>
      <div id="container">
        <div class="header">您的文章：《'. get_the_title($comment->comment_post_ID) .'》有新评论啦！</div>
        <div style="width:90%; margin:0 auto">
          <p>作者：'. $comment->comment_author .'</p>
          <p>电子邮箱：'. $comment->comment_author_email .'</p>
          <p>URL：'. $comment->comment_author_url .'</p>
          <p>评论内容：</p>
          <p class="comment-content">' . xm_output_smiley($comment->comment_content) . '</p>
        </div>
      </div>
    ';
    $message_headers = "Content-Type: text/html; charset=utf-8;";
    // 为新评论时才发送邮件
    if ($comment->comment_approved == 0 && $to != '') {
        wp_mail($to, $title, $message, $message_headers);
    }
}
add_action('wp_insert_comment', 'xm_new_comment');

// 添加svg文件上传
function xm_upload_mimes($mimes = array())
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'xm_upload_mimes');

//新建或更新文章时移除 noreferrer
function xm_targeted_link_rel_remove_noreferrer($rel_values)
{
    return preg_replace('/noreferrer\s*/i', '', $rel_values);
}
add_filter('wp_targeted_link_rel', 'xm_targeted_link_rel_remove_noreferrer', 999);

//新建或更新文章时移除 noopener
function xm_targeted_link_rel_remove_noopener($rel_values)
{
    return preg_replace('/noopener\s*/i', '', $rel_values);
}
add_filter('wp_targeted_link_rel', 'xm_targeted_link_rel_remove_noopener', 999);
?>
