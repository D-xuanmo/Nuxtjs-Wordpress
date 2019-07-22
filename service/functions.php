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
// function my_prefix_remove_admin_bar_item($wp_admin_bar)
// {
//   $wp_admin_bar->remove_node('site-name');
// }
// add_action('admin_bar_menu', 'my_prefix_remove_admin_bar_item', 999);

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
        var aLanguage = ['html', 'css', 'sass', 'scss', 'less', 'javascript', 'php', 'json', 'http', 'nginx'];
        for (var i = 0, length = aLanguage.length; i < length; i++) {
            QTags.addButton(aLanguage[i], aLanguage[i], '\n<pre class="language-' + aLanguage[i] + ' line-numbers"><code class="language-' + aLanguage[i] + '">\n', '\n</code></pre>\n');
        }
        QTags.addButton('h2', 'h2', '<h2>', '</h2>');
        QTags.addButton('2-text', '2-text', '<span style="display:inline-block; width:28px;">', '</span>');
        QTags.addButton('star', 'star', '<i class="iconfont icon-star c-theme">', '</i>');
        QTags.addButton('arrow-right', 'arrow-right', '<i class="iconfont icon-arrow-right-f">', '</i>');
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
function xm_custom_smilies_src($img_src, $img) {
  return get_option("xm_vue_options")["domain"] . '/images/smilies/' . $img;
}
add_filter('smilies_src', 'xm_custom_smilies_src', 10, 2);

function xm_custom_smilies_conversion () {
  global $wpsmiliestrans;
  if (!isset($wpsmiliestrans)) {
    $wpsmiliestrans = array(
      "[hashiqi]" => "hashiqi.png",
      "[huaji]" => "huaji.png",
      "[doge1]" => "doge.png",
      "[weixiao]" => "weixiao.gif",
      "[nanguo]" => "nanguo.gif",
      "[qiudale]" => "qiudale.gif",
      "[penxue]" => "penxue.gif",
      "[piezui]" => "piezui.gif",
      "[aoman]" => "aoman.gif",
      "[baiyan]" => "baiyan.gif",
      "[bishi]" => "bishi.gif",
      "[bizui]" => "bizui.gif",
      "[cahan]" => "cahan.gif",
      "[ciya]" => "ciya.gif",
      "[dabing]" => "dabing.gif",
      "[daku]" => "daku.gif",
      "[deyi]" => "deyi.gif",
      "[doge]" => "doge.gif",
      "[fadai]" => "fadai.gif",
      "[fanu]" => "fanu.gif",
      "[fendou]" => "fendou.gif",
      "[ganga]" => "ganga.gif",
      "[guzhang]" => "guzhang.gif",
      "[haixiu]" => "haixiu.gif",
      "[hanxiao]" => "hanxiao.gif",
      "[haqian]" => "haqian.gif",
      "[huaixiao]" => "huaixiao.gif",
      "[jie]" => "jie.gif",
      "[jingkong]" => "jingkong.gif",
      "[jingxi]" => "jingxi.gif",
      "[jingya]" => "jingya.gif",
      "[keai]" => "keai.gif",
      "[kelian]" => "kelian.gif",
      "[koubi]" => "koubi.gif",
      "[ku]" => "ku.gif",
      "[kuaikule]" => "kuaikule.gif",
      "[kulou]" => "kulou.gif",
      "[kun]" => "kun.gif",
      "[leiben]" => "leiben.gif",
      "[lenghan]" => "lenghan.gif",
      "[liuhan]" => "liuhan.gif",
      "[liulei]" => "liulei.gif",
      "[qiaoda]" => "qiaoda.gif",
      "[qinqin]" => "qinqin.gif",
      "[saorao]" => "saorao.gif",
      "[se]" => "se.gif",
      "[shuai]" => "shuai.gif",
      "[shui]" => "shui.gif",
      "[tiaopi]" => "tiaopi.gif",
      "[touxiao]" => "touxiao.gif",
      "[tu]" => "tu.gif",
      "[tuosai]" => "tuosai.gif",
      "[weiqu]" => "weiqu.gif",
      "[wozuimei]" => "wozuimei.gif",
      "[wunai]" => "wunai.gif",
      "[xia]" => "xia.gif",
      "[xiaojiujie]" => "xiaojiujie.gif",
      "[xiaoku]" => "xiaoku.gif",
      "[xieyanxiao]" => "xieyanxiao.gif",
      "[xu]" => "xu.gif",
      "[yinxian]" => "yinxian.gif",
      "[yiwen]" => "yiwen.gif",
      "[zuohengheng]" => "zuohengheng.gif",
      "[youhengheng]" => "youhengheng.gif",
      "[yun]" => "yun.gif",
      "[zaijian]" => "zaijian.gif",
      "[zhayanjian]" => "zhayanjian.gif",
      "[zhemo]" => "zhemo.gif",
      "[zhouma]" => "zhouma.gif",
      "[zhuakuang]" => "zhuakuang.gif",
      "[aini]" => "aini.gif",
      "[baoquan]" => "baoquan.gif",
      "[gouyin]" => "gouyin.gif",
      "[qiang]" => "qiang.gif",
      "[OK]" => "OK.gif",
      "[woshou]" => "woshou.gif",
      "[quantou]" => "quantou.gif",
      "[shengli]" => "shengli.gif",
      "[aixin]" => "aixin.gif",
      "[bangbangtang]" => "bangbangtang.gif",
      "[baojin]" => "baojin.gif",
      "[caidao]" => "caidao.gif",
      "[lanqiu]" => "lanqiu.gif",
      "[chi]" => "chi.gif",
      "[dan]" => "dan.gif",
      "[haobang]" => "haobang.gif",
      "[hecai]" => "hecai.gif",
      "[hexie]" => "hexie.gif",
      "[juhua]" => "juhua.gif",
      "[pijiu]" => "pijiu.gif",
      "[shouqiang]" => "shouqiang.gif",
      "[xiaoyanger]" => "xiaoyanger.gif",
      "[xigua]" => "xigua.gif",
      "[yangtuo]" => "yangtuo.gif",
      "[youling]" => "youling.gif",
      "[色]" => 'icon_razz.gif',
      "[难过]" => 'icon_sad.gif',
      "[闭嘴]" => 'icon_evil.gif',
      "[吐舌头]" => 'icon_exclaim.gif',
      "[微笑]" => 'icon_smile.gif',
      "[可爱]" => 'icon_redface.gif',
      '[kiss]' => 'icon_biggrin.gif',
      "[惊讶]" => 'icon_surprised.gif',
      "[饥饿]" => 'icon_eek.gif',
      "[晕]" => 'icon_confused.gif',
      "[酷]" => 'icon_cool.gif',
      "[坏笑]" => 'icon_lol.gif',
      "[发怒]" => 'icon_mad.gif',
      "[憨笑]" => 'icon_twisted.gif',
      "[萌萌哒]" => 'icon_rolleyes.gif',
      "[吃东西]" => 'icon_wink.gif',
      "[色咪咪]" => 'icon_idea.gif',
      "[囧]" => 'icon_arrow.gif',
      "[害羞]" => 'icon_neutral.gif',
      "[流泪]" => 'icon_cry.gif',
      "[流汗]" => 'icon_question.gif',
      "[你懂的]" => 'icon_mrgreen.gif'
    );
  }
}
add_action('init','xm_custom_smilies_conversion', 3);

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
    // 邮件内容，自行修改，支持HTML
    $message = '
      <div style="width:90%; margin:10px auto 0; border:1px solid #eee; border-radius:8px; font-size:12px; font-family:PingFangSC-Regular,Microsoft Yahei; color:#111;">
        <div style="width:100%; height:60px; border-radius:6px 6px 0 0; background:#eee; color:#333;">
          <p style="margin:0 0 0 30px; line-height:60px;"> 您在 <a style="text-decoration:none; color:#2ebef3; font-weight:600;" href="' . get_option('xm_vue_options')['domain'] . '">' . get_option('blogname') . '  </a> 的留言有新回复啦！</p>
        </div>
        <div style="width:90%; margin:0 auto">
          <p><strong>' . $parent_comment->comment_author . '</strong> 您好!</p>
          <p>您在 [' . get_option('blogname') . '] 的文章<strong style="color:#2ebef3;">《' . get_the_title($comment->comment_post_ID) . '》</strong>上发表的评论有新回复啦，快来看看吧 ^_^:</p>
          <p>这是你的评论:</p>
          <p style="margin: 15px 0; padding: 20px; border-radius: 5px; background-color: #eee;">' . xm_output_smiley(trim($parent_comment->comment_content)) . '</p>
          <p><strong>' . trim($comment->comment_author) . '</strong> 给你的回复是:<br />
          <p style="margin: 15px 0; padding: 20px; border-radius: 5px; background-color: #eee;">' . xm_output_smiley(trim($comment->comment_content)) . '</p>
          <p>您也可移步到文章<a style="text-decoration:none; color:#2ebef3" href="' . get_option('xm_vue_options')['domain'] . '/details/' . $comment->comment_post_ID . '"> 《' . get_the_title($comment->comment_post_ID) . '》 </a>查看完整回复内容</p>
          <p style="padding-bottom: 10px; border-bottom: 1px dashed #ccc;">欢迎再次光临 <a style="text-decoration:none; color:#2ebef3" href="' . get_option('xm_vue_options')['domain'] . '">' . get_option('blogname') . '</a></p>
          <p style="color: f00;">(此邮件由系统自动发出, 请勿回复。)</p>
        </div>
      </div>';
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

// 有人评论时通知管理员
function xm_new_comment($comment_id) {
  $to = get_bloginfo('admin_email');
  $comment = get_comment($comment_id);
  $title = '['. get_option('blogname') .'] 新评论："'. get_the_title($comment->comment_post_ID) .'"';
  $message = '<style>.comment-content { font-size: 14px; }</style>'
            .'<h2 style="font-size: 16px;">您的文章：《'. get_the_title($comment->comment_post_ID) .'》有新评论</h2>'
            .'<p class="comment-content">作者：'. $comment->comment_author .'</p>'
            .'<p class="comment-content">电子邮箱：'. $comment->comment_author_email .'</p>'
            .'<p class="comment-content">URL：'. $comment->comment_author_url .'</p>'
            .'<p class="comment-content">评论：'. xm_output_smiley($comment->comment_content) .'</p>';
  $message_headers = "Content-Type: text/html; charset=utf-8;";
  // 为新评论时才发送邮件
  if ($comment->comment_approved == 0 && $to != '') wp_mail($to, $title, $message, $message_headers);
}
add_action('wp_insert_comment', 'xm_new_comment');

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
?>
