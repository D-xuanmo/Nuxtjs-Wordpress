<?php
// 引入浏览器和系统正则
require_once(TEMPLATEPATH."/include/xm-comment-extra.php");

require_once(TEMPLATEPATH."/utils.php");

global $colors;
$colors = ["#f3a683", "#778beb", "#e77f67", "#f5cd79", "#0fb9b1", "#e77f67", "#f8a5c2", "#596275", "#2196F3", "#fb683a"];

/**
 * 删除不需要的字段
 */
function xm_rest_prepare_post($data, $post, $request)
{
    $_data = $data -> data;
    $params = $request -> get_params();
    unset($_data["template"]);
    unset($_data["categories"]);
    unset($_data["excerpt"]);
    $data -> data = $_data;
    return $data;
}
add_filter("rest_prepare_post", "xm_rest_prepare_post", 10, 3);

/**
 * 获取page添加自定义字段
 */
function add_api_page_meta_field()
{
    register_rest_field("page", "pageInfor", array(
    "get_callback" => function () {
        return array("commentCount" => get_comments_number());
    },
    "schema" => null,
  ));
}
add_action("rest_api_init", "add_api_page_meta_field");

/**
 * 文章添加自定义字段
 */
function xm_get_article_infor($object)
{
    $postID = $object["id"];
    // 添加发表意见默认值
    if (get_post_meta($postID, "xm_post_link", true) === "") {
        add_post_meta($postID, "xm_post_link", array(
            "very_good" => 0,
            "good" => 0,
            "commonly" => 0,
            "bad" => 0,
            "very_bad" => 0
        ));
    }
    $current_category = get_the_category($postID);
    $array = array(
    "author" => get_the_author(),
    "other" => array(
        "authorPic" => get_the_author_meta("simple_local_avatar")[full],
        "authorTro" => get_the_author_meta("description"),
        "github" => get_the_author_meta("github_url"),
        "qq" => get_the_author_meta("qq"),
        "wechatNum" => get_the_author_meta("wechat_num"),
        "wechatPic" => get_the_author_meta("wechat_img"),
        "sina" => get_the_author_meta("sina_url"),
        "email" => get_the_author_meta("user_email"),
    ),
    "thumbnail" => wp_get_attachment_image_src(get_post_thumbnail_id($postID), "Full")[0],
    "viewCount" => get_post_meta($postID, "post_views_count", true) === "" ? 0 : get_post_meta($postID, "post_views_count", true),
    "commentCount" => get_comments_number(),
    "xmLike" => get_post_meta($postID, "xm_post_link", true),
    "summary" => xm_get_post_excerpt(160, ""),
    "classify" => get_the_category(),
    "tags" => get_the_tags($postID),
    "prevLink" => get_previous_post($current_category, ""),
    "nextLink" => get_next_post($current_category, "")
  );
    return $array;
}
add_action("rest_api_init", function () {
    register_rest_field("post", "articleInfor", array("get_callback" => "xm_get_article_infor", "schema" => null,));
});

/**
 * 获取用户添加自定义字段
 */
function add_api_user_meta_field()
{
    register_rest_field("user", "meta", array(
    "get_callback" => function () {
        $id = intval($_GET["id"]);
        $array = array(
            "qq" => get_the_author_meta("qq", $id),
            "github" => get_the_author_meta("github_url", $id),
            "wechat_num" => get_the_author_meta("wechat_num", $id),
            "wechat_img" => get_the_author_meta("wechat_img", $id),
            "sina_url" => get_the_author_meta("sina_url", $id),
            "sex" => get_the_author_meta("sex", $id)
        );
        return $array;
    },
    "schema" => null,
  ));
}
add_action("rest_api_init", "add_api_user_meta_field");

/**
 * 获取网站基本信息
 */
function add_get_blog_info()
{
    global $wpdb;
    global $colors;

    // 获取最后更新时间
    $last = $wpdb -> get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");
    $last = date('Y-n-j', strtotime($last[0] -> MAX_m));

    // 获取最新评论
    $newComment = get_comments(array(
        "number" => 10,
        "status" => "approve",
        "type" => "comment",
        "user_id" => 0
    ));
    $latestComment = array();
    for ($i = 0; $i < count($newComment); $i++) {
        preg_match("/\d/", md5($newComment[$i]->comment_author_email), $matches);
        $latestComment[$i]->avatar = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($newComment[$i]->comment_author_email))) . "?s=200";
        $latestComment[$i]->background = $colors[$matches[0]]; // 根据邮箱md5后获取第一个数字生成颜色
        $latestComment[$i]->countCom = get_comments_number($newComment[$i]->comment_post_ID);
        $latestComment[$i]->link = get_post_meta($newComment[$i]->comment_post_ID, "xm_post_link", true)["very_good"];
        $latestComment[$i]->title = get_the_title($newComment[$i]->comment_post_ID);
        $latestComment[$i]->author = $newComment[$i]->comment_author;
        $latestComment[$i]->content = xm_output_smiley($newComment[$i]->comment_content);
        $latestComment[$i]->id = $newComment[$i]->comment_post_ID;
        $latestComment[$i]->postType = get_post($newComment[$i]->comment_post_ID)->post_type;
    }

    $xm_options = get_option("xm_vue_options");
    $result = array(
        "alipay" => $xm_options["alipay"],
        "banner" => $xm_options["banner"],
        "blogDescription" => get_bloginfo("description"),
        "blogName" => get_bloginfo("name"),
        "contentUrl" => "/wp-content",
        "copyright" => $xm_options["footer_copyright"],
        "description" => $xm_options["description"],
        "detailsCss" => $xm_options["details_css"],
        "domain" => get_option("xm_vue_options")["domain"],
        "favicon" => $xm_options["favicon"],
        "getAllCountArticle" => wp_count_posts() -> publish,
        "getAllCountCat" => wp_count_terms("category"),
        "getAllCountComment" => $wpdb -> get_var("SELECT COUNT(*) FROM $wpdb->comments"),
        "getAllCountPage" => wp_count_posts("page") -> publish,
        "getAllCountTag" => wp_count_terms("post_tag"),
        "globalCss" => $xm_options["global_css"],
        "isOpenArticleCopyright" => (bool) $xm_options["article_copyright"],
        "isOpenAsideCount" => (bool) $xm_options["aside_count"],
        "isOpenCommentUpload" => (bool) $xm_options["is_open_comment_upload"],
        "isOpenReward" => (bool) $xm_options["is_open_reward"],
        "isOpenTextThumbnail" => (bool) $xm_options["text_pic"],
        "keywords" => $xm_options["keywords"],
        "lastUpDate" => $last,
        "link" => $xm_options["link"],
        "logo" => $xm_options["logo"],
        "newComment" => $latestComment,
        "notice" => $xm_options["sidebar_notice"],
        "rewardText" => $xm_options["reward_text"],
        "tagCloud" => get_tags(array("orderby" => "count", "order" => "DESC")),
        "templeteUrl" => "/wp-content/themes/" . get_option("template"),
        "themeDir" => get_option("template"),
        "thumbnail" => $xm_options["thumbnail"],
        "wechatpay" => $xm_options["wechatpay"]
        // "newArticle" => $wpdb -> get_results("SELECT ID,post_title FROM $wpdb->posts where post_status='publish' and post_type='post' ORDER BY ID DESC LIMIT 0 , 10"),
    );
    return $result;
}
add_action("rest_api_init", function () {
    register_rest_route("xm-blog/v1", "/info", array("methods" => "GET", "callback" => "add_get_blog_info"));
});

/**
 * 发表意见
 */
function xm_opinion($request)
{
    $data = $request -> get_params();
    $count_key = "xm_post_link";
    $id = $data["id"];
    $key = $data["key"];
    $count = get_post_meta($id, $count_key, true);
    update_post_meta($id, $count_key, array_merge($count, array($key => $count[$key] + 1)));
    return get_post_meta($id, $count_key, true);
}
add_action("rest_api_init", function () {
    register_rest_route("xm-blog/v1", "/like", array("methods" => "POST", "callback" => "xm_opinion"));
});

/**
 * 更新阅读量
 */
function xm_get_view_count($request)
{
    $postID = $request -> get_params()["id"];
    $count_key = "post_views_count";
    $count = get_post_meta($postID, $count_key, true);
    if ($count == "") {
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, "1");
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
    return $count;
}
add_action("rest_api_init", function () {
    register_rest_route("xm-blog/v1", "/view-count", array("methods" => "POST", "callback" => "xm_get_view_count",));
});

/**
 * 获取主菜单
 */
function xm_get_menu()
{
    $mainMenu = [];
    $sourceMenu = wp_get_nav_menu_items("Home");
    foreach ($sourceMenu as $value) {
        $value->children = [];
        $value->classes = $value->classes[0];
        for ($i = 0; $i < count($sourceMenu); $i++) {
            if ($sourceMenu[$i]->menu_item_parent == $value->ID) {
                array_push($value->children, array_splice($sourceMenu, $i, 1, "")[0]);
            }
        }
    }
    foreach ($sourceMenu as $value) {
        $value && array_push($mainMenu, $value);
    }
    return array(
        "mainMenu" => $mainMenu,
        "subMenu" => wp_get_nav_menu_items("SubMenu")
    );
}
add_action("rest_api_init", function () {
    register_rest_route("xm-blog/v1", "/menu", array("methods" => "GET", "callback" => "xm_get_menu"));
});

/**
 * 评论列表增加点赞
 */
function add_api_comment_metadata($request)
{
    $postID = $request -> get_params()["id"];
    $key = $request -> get_params()["type"];
    $result = get_metadata("comment", $postID, "opinion", true);
    return array(
        "success" => update_metadata("comment", $postID, "opinion", array_merge($result, array($key => $result[$key] + 1))),
        "data" => get_metadata("comment", $postID, "opinion", true)
    );
}
add_action("rest_api_init", function () {
    register_rest_route("xm-blog/v1", "/update-comment-meta", array("methods" => "POST", "callback" => "add_api_comment_metadata"));
});

/**
 * 获取说说列表
 */
function add_api_get_phrase ()
{
    global $wpdb;
    $list = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'phrase' AND post_status = 'publish' ORDER BY post_date DESC");
    $result = [];
    for ($i = 0; $i < count($list); $i++) {
        $result[$i]->date = $list[$i]->post_date;
        // $result[$i]->title = $list[$i]->post_title;
        $result[$i]->content = xm_output_smiley($list[$i]->post_content);
        $result[$i]->link = $list[$i]->post_excerpt;
        $result[$i]->avatar = replace_domain(get_the_author_meta("simple_local_avatar", $list[$i]->post_author)[full]);
    }
    return array(
        'success' => true,
        "data" => $result
    );
}
add_action("rest_api_init", function () {
    register_rest_route("xm-blog/v1", "/get-phrase", array("methods" => "get", "callback" => "add_api_get_phrase"));
});

/**
 * wordpress接口增加字段
 */
function add_api_comment_meta_field()
{
    // 评论添加字段
    register_rest_field("comment", "userAgentInfo", array(
        "get_callback" => function ($object) {
            global $colors;
            preg_match("/\d/", md5($object[author_email]), $matches);
            $array = array(
                "userAgent" => get_browser_name($object[author_user_agent]),
                "vipStyle" => get_author_class($object[author_email]),
                "author_avatar_urls" => "https://www.gravatar.com/avatar/" . md5(strtolower(trim($object[author_email]))) . "?s=200",
                "background" => $colors[$matches[0]] // 根据邮箱md5后获取第一个数字生成颜色
            );
            return $array;
        },
        "schema" => null
    ));

    register_rest_field("comment", "meta", array(
        "get_callback" => function ($object) {
            if (!get_metadata("comment", $object[id], "opinion", true)) {
                add_metadata("comment", $object[id], "opinion", array("good" => 0, "bad" => 0), true);
            }
            return array(
                "opinion" => get_metadata("comment", $object[id], "opinion", true)
            );
        },
        "schema" => null
    ));

    // 文章格式化
    // register_rest_field(["post", "page", "comment"], "content", array(
    //   "get_callback" => function ($object) {
    //     return array(
    //       "rendered" => xm_output_smiley($object[content][rendered])
    //     );
    //   },
    //   "schema" => null
    // ));
}
add_action("rest_api_init", "add_api_comment_meta_field");
