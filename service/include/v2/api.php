<?php
require_once(TEMPLATEPATH . '/include/v2/Response.php');

/**
 * 获取全站的文章、分类、标签、页面
 * @return array
 */
function get_all_list() {
    global $wpdb;
    $response = new Response();

    // 查出所有的文章
    $article = $wpdb->get_results("SELECT id, post_title AS title FROM $wpdb->posts WHERE (post_status = 'publish' OR post_status = 'private') AND post_type='post' ORDER BY post_date DESC");

    // 查出所有的页面
    $pages = $wpdb->get_results("SELECT id, post_title AS title FROM $wpdb->posts WHERE (post_status = 'publish' OR post_status = 'private') AND post_type='page' ORDER BY post_date DESC");

    // 所有的标签
    $tags = get_tags(array("orderby" => "count", "order" => "DESC"));
    foreach ($tags as $key => $value) {
        $tags[$key] = array(
            'id'    => $value->term_id,
            'title' => $value->name
        );
    }

    // 所有的分类
    $categorys = wp_get_nav_menu_items("Home");
    foreach ($categorys as $key => $value) {
        $categorys[$key] = array(
            'id'    => $value->ID,
            'title' => $value->title
        );
    }

    $response->setResponse(array(
        'article'  => $article,
        'page'     => $pages,
        'tags'     => $tags,
        'category' => $categorys
    ));
    return $response->getResponse();
}

add_action('rest_api_init', function () {
    register_rest_route('xm/v2', '/site/list/all', array(
        'methods'             => 'get',
        'permission_callback' => '__return_true',
        'callback'            => 'get_all_list'
    ));
});
