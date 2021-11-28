<?php
require_once(TEMPLATEPATH . '/v2/Response.php');

/**
 * 获取全站的文章、分类、标签、页面
 * @return array
 */
function get_all_list(): array {
    global $wpdb;
    $response = new Response();

    // 查出所有的文章
    $article = $wpdb->get_results("SELECT id, post_title AS title, post_date AS createTime FROM $wpdb->posts WHERE (post_status = 'publish' OR post_status = 'private') AND post_type='post' ORDER BY post_date DESC");

    // 查出所有的页面
    $pages = $wpdb->get_results("SELECT id, post_title AS title, post_date AS createTime FROM $wpdb->posts WHERE (post_status = 'publish' OR post_status = 'private') AND post_type='page' ORDER BY post_date DESC");

    // 所有的标签
    $tags = get_tags(array("orderby" => "count", "order" => "DESC"));
    foreach ($tags as $key => $value) {
        $tags[$key] = array(
            'id'    => $value->term_id,
            'title' => $value->name,
            'count' => $value->count
        );
    }

    // 所有的分类
    $categorys = wp_get_nav_menu_items("Home");
    foreach ($categorys as $key => $value) {
        $categorys[$key] = array(
            'id'    => $value->object_id,
            'title' => $value->title
        );
    }

    $response->setResponse(array(
        'articles' => $article,
        'pages'    => $pages,
        'tags'     => $tags,
        'category' => $categorys
    ));
    return $response->getResponse();
}

add_action('rest_api_init', function () {
    register_rest_route('/xm/v2', '/site/list/all', array(
        'methods'             => 'get',
        'permission_callback' => '__return_true',
        'callback'            => 'get_all_list'
    ));
});

/**
 * 查询评论列表
 * @return array
 */
function get_comment_list(): array {
    $response = new Response();

    $page = empty($_GET['page']) ? 1 : $_GET['page'];
    $page_size = empty($_GET['pageSize']) ? 8 : $_GET['pageSize'];

    $comment_list = get_comments(array(
        'post_id' => $_GET['postId'],
        'parent'  => 0,
        'number'  => $page_size,
        'paged'   => $page,
        'status'  => 'approve'
    ));

    $result = array();

    // 循环查询子数据
    foreach ($comment_list as $key => $list) {
        $children_response = get_comments(array(
            'post_id' => $_GET['postId'],
            'parent'  => $list->comment_ID,
            'order'   => 'ASC',
            'status'  => 'approve'
        ));
        $children = array();

        foreach ($children_response as $child_key => $child) {
            $children[$child_key] = xm_format_comment_item($child);
        }

        $result[$key] = xm_format_comment_item($list);
        $result[$key]['children'] = $children;
    }

    $totalCount = (int)get_comments(array(
        'post_id' => $_GET['postId'],
        'count'   => true,
        'parent'  => 0,
        'status'  => 'approve'
    ));

    $response->setPageData(array(
        'page'      => (int)$page,
        'pageSize'  => (int)$page_size,
        'data'      => $result,
        'total'     => $totalCount,
        'totalPage' => ceil($totalCount / $page_size)
    ));

    return $response->getResponse();
}

add_action('rest_api_init', function () {
    register_rest_route('/xm/v2', '/comment/list', array(
        'methods'             => 'get',
        'permission_callback' => '__return_true',
        'callback'            => 'get_comment_list'
    ));
});
