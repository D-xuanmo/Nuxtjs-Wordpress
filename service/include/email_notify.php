<?php

/**
 * 邮件回复
 */
function ludou_comment_mail_notify($comment_id, $comment_status) {
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

/**
 * 修改发件人名字为博客名字
 * @param $email
 * @return false|mixed|void
 */
function xm_new_from_name($email) {
    return get_option('blogname');
}

add_filter('wp_mail_from_name', 'xm_new_from_name');

/**
 * 有人评论时通知管理员
 * @param $comment_id
 */
function xm_new_comment($comment_id) {
    $secret = get_option('xm_qywx_secret');
    $to = get_bloginfo('admin_email');
    $comment = get_comment($comment_id);
    $title = '[' . get_option('blogname') . '] 新评论："' . get_the_title($comment->comment_post_ID) . '"';
    qywx_notify("
        站点有新评论到达，记得查看哦~
        <br>文章标题：" . get_the_title($comment->comment_post_ID) . "
        <br>作者: " . $comment->comment_author . "
        <br>电子邮箱: " . $comment->comment_author_email . "
        <br>URL: " . $comment->comment_author_url . "
        <br>评论内容: " . xm_output_smiley($comment->comment_content)
    );
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
        <div class="header">您的文章：《' . get_the_title($comment->comment_post_ID) . '》有新评论啦！</div>
        <div style="width:90%; margin:0 auto">
          <p>作者：' . $comment->comment_author . '</p>
          <p>电子邮箱：' . $comment->comment_author_email . '</p>
          <p>URL：' . $comment->comment_author_url . '</p>
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