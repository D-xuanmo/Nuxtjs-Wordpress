<?php
// 删除网址
function replace_domain ($url) {
  return preg_replace('/https?:\/\/(\w+\.)+\w+(:\d+)?/', '', $url);
}

// 获取头像
function local_avatar_url () {
  if (get_the_author_meta('simple_local_avatar') === '') {
    return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(get_the_author_meta('email')))) . '?s=200';
  } else {
    return get_the_author_meta('simple_local_avatar')[full];
  }
}

/**
 * 删除不需要的字段
 */
function xm_rest_prepare_post ($data, $post, $request) {
  $_data = $data -> data;
  $params = $request -> get_params();
  unset($_data['template']);
  unset($_data['categories']);
  unset($_data['excerpt']);
  $data -> data = $_data;
  return $data;
}
add_filter('rest_prepare_post', 'xm_rest_prepare_post', 10, 3);

/**
 * 获取page添加自定义字段
 */
function add_api_page_meta_field () {
  register_rest_field('page', 'pageInfor', array(
    'get_callback' => function () {
      return array('commentCount' => get_comments_number());
    },
    'schema' => null,
  ));
}
add_action('rest_api_init', 'add_api_page_meta_field');

/**
 * 文章添加自定义字段
 */
function xm_get_article_infor ($object) {
  $postID = $object['id'];
  // 添加发表意见默认值
  if (get_post_meta($postID, 'xm_post_link', true) === '') {
    add_post_meta($postID, 'xm_post_link', array(
      'very_good' => 0,
      'good' => 0,
      'commonly' => 0,
      'bad' => 0,
      'very_bad' => 0
    ));
  }
  $current_category = get_the_category($postID);
  $array = array(
    'author' => get_the_author(),
    'other' => array(
      'authorPic' => local_avatar_url(),
      'authorTro' => get_the_author_meta('description'),
      'github' => get_the_author_meta('github_url'),
      'qq' => get_the_author_meta('qq'),
      'wechatNum' => get_the_author_meta('wechat_num'),
      'wechatPic' => get_the_author_meta('wechat_img'),
      'sina' => get_the_author_meta('sina_url'),
      'email' => get_the_author_meta('user_email'),
    ),
    'thumbnail' => wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'Full')[0],
    'viewCount' => get_post_meta($postID, 'post_views_count', true) === '' ? 0 : get_post_meta($postID, 'post_views_count', true),
    'commentCount' => get_comments_number(),
    'xmLike' => get_post_meta($postID, 'xm_post_link', true),
    'summary' => xm_get_post_excerpt(300, ''),
    'classify' => get_the_category(),
    'tags' => get_the_tags($postID),
    'prevLink' => get_previous_post($current_category, ''),
    'nextLink' => get_next_post($current_category, '')
  );
  return $array;
}
add_action('rest_api_init', function () {
  register_rest_field('post', 'articleInfor', array('get_callback' => 'xm_get_article_infor', 'schema' => null,));
});

/**
 * 获取用户添加自定义字段
 */
function add_api_user_meta_field () {
  register_rest_field('user', 'meta', array(
    'get_callback' => function () {
      $id = intval($_GET['id']);
      $array = array(
        'qq' => get_the_author_meta('qq', $id),
        'github' => get_the_author_meta('github_url', $id),
        'wechat_num' => get_the_author_meta('wechat_num', $id),
        'wechat_img' => get_the_author_meta('wechat_img', $id),
        'sina_url' => get_the_author_meta('sina_url', $id),
        'sex' => get_the_author_meta('sex', $id)
      );
      return $array;
    },
    'schema' => null,
  ));
}
add_action('rest_api_init', 'add_api_user_meta_field');

/**
 * 获取网站基本信息
 */
function add_get_blog_info () {
  global $wpdb;

  // 获取最后更新时间
  $last = $wpdb -> get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");
  $last = date('Y-n-j', strtotime($last[0] -> MAX_m));

  // 获取最新评论
  $newComment = get_comments(array(
    'number' => 10,
    'status' => 'approve',
    'type' => 'comment',
    'user_id' => 0,
    'post_type' => 'post'
  ));
  for ($i = 0; $i < count($newComment); $i++) {
    $newComment[$i] -> avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($newComment[$i] -> comment_author_email))) . '?s=200';
    $newComment[$i] -> background = '#' . substr(md5(strtolower(trim($newComment[$i] -> comment_author_email))), 0, 6);
    $newComment[$i] -> countCom = get_comments_number($newComment[$i] -> comment_post_ID);
    $newComment[$i] -> link = get_post_meta($newComment[$i] -> comment_post_ID, 'xm_post_link', true)['very_good'];
    $newComment[$i] -> title = get_the_title($newComment[$i] -> comment_post_ID);
  }

  $xm_options = get_option('xm_vue_options');
  $result = array(
    'domain' => get_option('xm_vue_options')['domain'],
    'isOpenAsideCount' => (bool) $xm_options['aside_count'],
    'isOpenCommentUpload' => (bool) $xm_options['is_open_comment_upload'],
    'isOpenTextThumbnail' => (bool) $xm_options['text_pic'],
    'isOpenArticleCopyright' => (bool) $xm_options['article_copyright'],
    'isOpenReward' => (bool) $xm_options['is_open_reward'],
    'rewardText' => $xm_options['reward_text'],
    'alipay' => $xm_options['alipay'],
    'wechatpay' => $xm_options['wechatpay'],
    'contentUrl' => '/wp-content',
    'themeDir' => get_option('template'),
    'templeteUrl' => $xm_options['domain'] . '/wp-content/themes/' . get_option('template'),
    'detailsCss' => $xm_options['details_css'],
    'blogName' => get_bloginfo('name'),
    'blogDescription' => get_bloginfo('description'),
    'adminPic' => local_avatar_url(),
    'banner' => $xm_options['banner'],
    'thumbnail' => $xm_options['thumbnail'],
    'copyright' => $xm_options['footer_copyright'],
    'notice' => $xm_options['sidebar_notice'],
    'logo' => $xm_options['logo'],
    'tagCloud' => get_tags(array('orderby' => 'count', 'order' => 'DESC')),
    'getAllCountArticle' => wp_count_posts() -> publish,
    'getAllCountCat' => wp_count_terms('category'),
    'getAllCountTag' => wp_count_terms('post_tag'),
    'getAllCountPage' => wp_count_posts('page') -> publish,
    'getAllCountComment' => $wpdb -> get_var("SELECT COUNT(*) FROM $wpdb->comments"),
    'lastUpDate' => $last,
    'link' => $xm_options['link'],
    'newArticle' => $wpdb -> get_results("SELECT ID,post_title FROM $wpdb->posts where post_status='publish' and post_type='post' ORDER BY ID DESC LIMIT 0 , 10"),
    'newComment' => $newComment
  );
  return $result;
}
add_action('rest_api_init', function () {
  register_rest_route('xm-blog/v1', '/info', array('methods' => 'GET', 'callback' => 'add_get_blog_info'));
});

/**
 * 发表意见
 */
function xm_opinion ($request) {
  $data = $request -> get_params();
  $count_key = 'xm_post_link';
  $id = $data['id'];
  $key = $data['key'];
  $count = get_post_meta($id, $count_key, true);
  update_post_meta($id, $count_key, array_merge($count, array($key => $count[$key] + 1)));
  return get_post_meta($id, $count_key, true);
}
add_action('rest_api_init', function () {
  register_rest_route('xm-blog/v1', '/like', array('methods' => 'POST', 'callback' => 'xm_opinion'));
});

/**
 * 更新阅读量
 */
function xm_get_view_count ($request) {
  $postID = $request -> get_params()['id'];
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if ($count == '') {
    $count = 1;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '1');
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
  return $count;
}
add_action('rest_api_init', function () {
  register_rest_route('xm-blog/v1', '/view-count', array('methods' => 'POST', 'callback' => 'xm_get_view_count',));
});

/**
 * 获取主菜单
 */
function xm_get_menu () {
  $array_menu = wp_get_nav_menu_items('Home');
  $menu = array();
  foreach ($array_menu as $m) {
    if (empty($m -> menu_item_parent)) {
      $menu[$m -> ID] = array();
      $menu[$m -> ID]['ID'] = $m -> object_id;
      $menu[$m -> ID]['title'] = $m -> title;
      $menu[$m -> ID]['url'] = $m -> url;
      $menu[$m -> ID]['type'] = $m -> object;
      $menu[$m -> ID]['icon'] = implode(' ', $m -> classes);
      $menu[$m -> ID]['children'] = array();
    }
  }
  $submenu = array();
  foreach ($array_menu as $m) {
    if ($m -> menu_item_parent) {
      $submenu[$m -> ID] = array();
      $submenu[$m -> ID]['ID'] = $m -> object_id;
      $submenu[$m -> ID]['title'] = $m -> title;
      $submenu[$m -> ID]['url'] = $m -> url;
      $submenu[$m -> ID]['type'] = $m -> object;
      $submenu[$m -> ID]['icon'] = implode(' ', $m -> classes);
      $menu[$m -> menu_item_parent]['children'][$m -> ID] = $submenu[$m -> ID];
    }
  }
  return array(
    'mainMenu' => $menu,
    'subMenu' => wp_get_nav_menu_items('SubMenu')
  );
}
add_action('rest_api_init', function () {
  register_rest_route('xm-blog/v1', '/menu', array('methods' => 'GET', 'callback' => 'xm_get_menu'));
});

/**
 * [评论列表增加点赞]
 */
function add_api_comment_metadata ($request) {
  $postID = $request -> get_params()['id'];
  $key = $request -> get_params()['type'];
  $result = get_metadata('comment', $postID, 'opinion', true);
  return array(
    'success' => update_metadata('comment', $postID, 'opinion', array_merge($result, array($key => $result[$key] + 1))),
    'data' => get_metadata('comment', $postID, 'opinion', true)
  );
}
add_action('rest_api_init', function () {
  register_rest_route('xm-blog/v1', '/update-comment-meta', array('methods' => 'POST', 'callback' => 'add_api_comment_metadata'));
});

/**
 * 评论添加字段
 */
require_once(TEMPLATEPATH . '/include/xm-comment-extra.php');

function add_api_comment_meta_field () {
  register_rest_field('comment', 'userAgentInfo', array(
    'get_callback' => function ($object) {
      $array = array(
        'userAgent' => get_browser_name($object[author_user_agent]),
        'vipStyle' => get_author_class($object[author_email]),
        'author_avatar_urls' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($object[author_email]))) . '?s=200',
        'background' => '#' . substr(md5(strtolower(trim($object[author_email]))), 0, 6)
      );
      return $array;
    },
    'schema' => null
  ));

  register_rest_field('comment', 'meta', array(
    'get_callback' => function ($object) {
      if (!get_metadata('comment', $object[id], 'opinion', true)) {
        add_metadata('comment', $object[id], 'opinion', array('good' => 0, 'bad' => 0), true);
      }
      return array(
        'opinion' => get_metadata('comment', $object[id], 'opinion', true)
      );
    },
    'schema' => null
  ));
}
add_action('rest_api_init', 'add_api_comment_meta_field');
?>
