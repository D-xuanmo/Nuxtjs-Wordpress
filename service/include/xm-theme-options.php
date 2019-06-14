<?php
function themeoptions_admin_menu() {
	// 在控制面板的侧边栏添加设置选项页链接
	add_theme_page('xuan主题设置', 'xuan主题设置','edit_themes', basename(__FILE__), 'themeoptions_page');
}
if ( isset($_POST['update_themeoptions']) && $_POST['update_themeoptions'] == 'true' ) themeoptions_update();
function themeoptions_page() {
  // 获取提交的数据
  $xm_options = get_option('xm_vue_options');
  //加载上传图片的js(wp自带)
  wp_enqueue_script('thickbox');
  //加载css(wp自带)
  wp_enqueue_style('thickbox');
?>
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/include/css/set.css">
  <div class="container">
    <h2 class="title">主题设置</h2>
    <ul class="tab-wrapper">
      <li class="item is-active">基本</li>
      <li class="item">SEO</li>
      <li class="item">图片</li>
      <li class="item">社交</li>
      <li class="item">自定义代码</li>
      <li class="line"></li>
    </ul>
    <form method="post" action="">
      <input type="hidden" name="update_themeoptions" value="true">
      <ul class="content-wrapper">
        <!-- 内容一 基本 -->
        <li class="content-item content1">
					<div class="form-item">
						<p class="form-item-title">站点前端域名：</p>
						<div class="form-item-content">
							<input
								type="text"
								class="input-inner"
								name="domain"
								id="domain"
								value="<?php echo $xm_options['domain']; ?>"
							>
						</div>
					</div>

          <div class="form-item">
            <p class="form-item-title">侧边栏统计功能：</p>
            <div class="form-item-content">
              <label for="aside-count-on">开</label>
              <input
                type="radio"
                id="aside-count-on"
                name="aside-count"
                value="on" <?php if($xm_options['aside_count'] == 'on') echo 'checked'; ?>
              >
              <label for="aside-count-off">关</label>
              <input
                type="radio"
                id="aside-count-off"
                name="aside-count"
                value="off" <?php if($xm_options['aside_count'] == 'off' || $xm_options['aside_count'] == '') echo 'checked'; ?>
              >
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">开启评论上传图片功能：</p>
            <div class="form-item-content">
              <label for="comment-upload-on">开</label>
              <input
                type="radio"
                id="comment-upload-on"
                name="open-comment-upload"
                value="on" <?php if($xm_options['is_open_comment_upload'] == 'on') echo 'checked'; ?>
              >
              <label for="comment-upload-off">关</label>
              <input
                type="radio"
                id="comment-upload-off"
                name="open-comment-upload"
                value="off" <?php if($xm_options['is_open_comment_upload'] == 'off' || $xm_options['is_open_comment_upload'] == '') echo 'checked'; ?>
              >
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">是否开启文字头像：</p>
            <div class="form-item-content">
              <label for="open-text-picture-on">开</label>
              <input
                type="radio"
                id="open-text-picture-on"
                name="is-open-text-picture"
                value="on" <?php if($xm_options['text_pic'] == 'on') echo 'checked'; ?>
              >
              <label for="open-text-picture-off-off">关</label>
              <input
                type="radio"
                id="open-text-picture-off-off"
                name="is-open-text-picture"
                value="off" <?php if($xm_options['text_pic'] == 'off' || $xm_options['text_pic'] == '') echo 'checked'; ?>
              >
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">侧边栏公告：</p>
            <div class="form-item-content">
              <textarea class="input-inner" name="sidebar-notice" rows="5" cols="100"><?php echo $xm_options['sidebar_notice']; ?></textarea>
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">底部版权文字：</p>
            <div class="form-item-content">
              <textarea class="input-inner" name="footer-copyright" rows="7" cols="100"><?php echo $xm_options['footer_copyright']; ?></textarea>
            </div>
          </div>
        </li>

        <!-- 内容二 SEO -->
        <li class="content-item content2">
          <div class="form-item">
            <p class="form-item-title">首页关键词(keywords)：</p>
            <div class="form-item-content">
              <textarea class="input-inner" name="keywords" rows="3" cols="100"><?php echo $xm_options['keywords'] ?></textarea>
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">首页描述(description)：</p>
            <div class="form-item-content">
              <textarea class="input-inner" name="description" rows="8" cols="100"><?php echo $xm_options['description'] ?></textarea>
            </div>
          </div>
        </li>

  			<!-- 内容三 图片设置 -->
        <li class="content-item content3">
          <div class="form-item">
            <p class="form-item-title">评论区vip等级样式：</p>
            <div class="form-item-content">
              <label for="vip-style-1" class="vip-style" style="display: inline-block; width: 15px; height: 15px; background: url(<?php bloginfo('template_url'); ?>/static/images/vip.png) -147px -70px;"></label>
              <input
                type="radio"
                id="vip-style-1"
                name="vip-style"
                value="vip-style-1" <?php if($xm_options['vip_style'] == 'vip-style-1' || $xm_options['vip_style'] == '') echo 'checked'; ?>
              >
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">后台登录logo：</p>
            <div class="form-item-content">
              <input
                type="text"
                class="input-inner"
                name="login-logo"
                value="<?php echo $xm_options['login_logo']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件" class="choose-image">
              <p>
                <img src="<?php echo $xm_options['login_logo']; ?>" class="preview-img">
              </p>
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">导航logo：</p>
            <div class="form-item-content">
              <input
                type="text"
                class="input-inner"
                name="logo"
                id="logo"
                value="<?php echo $xm_options['logo']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件" class="choose-image">
              <p>
                <img src="<?php echo $xm_options['logo']; ?>" class="preview-img">
              </p>
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">文章列表默认缩略图：</p>
            <div class="form-item-content">
              <input
                type="text"
                class="input-inner"
                name="thumbnail-img"
                id="thumbnail-img"
                value="<?php echo $xm_options['thumbnail']; ?>">
              <input type="button" name="img-upload" value="选择文件" class="choose-image">
              <p>
                <img src="<?php echo $xm_options['thumbnail']; ?>" class="preview-img">
              </p>
            </div>
          </div>

          <div class="form-item-group">
            <div class="form-item">
              <p class="form-item-title">banner大图：</p>
              <div class="form-item-content">
                <input
                  type="text"
                  class="input-inner"
                  name="big-banner"
                  value="<?php echo $xm_options['banner']['big_banner']['path']; ?>"
                >
                <input type="button" name="img-upload" value="选择文件" class="choose-image">
                <p>
                  <img src="<?php echo $xm_options['banner']['big_banner']['path']; ?>" class="preview-img">
                </p>
              </div>
            </div>
            <div class="form-item">
              <p class="form-item-title">banner标题：</p>
              <div class="form-item-content">
                <input
                  type="text"
                  class="input-inner"
                  name="big-banner-text"
                  value="<?php echo $a_options['banner']['big_banner']['text']; ?>"
                >
              </div>
            </div>
            <div class="form-item">
              <p class="form-item-title">banner链接：</p>
              <div class="form-item-content">
                <input
                  type="text"
                  class="input-inner"
                  name="big-banner-text"
                  value="<?php echo $xm_options['banner']['big_banner']['link']; ?>"
                >
              </div>
            </div>
          </div>
  				<?php
  					for ($i = 1; $i < 4; $i++) {
  				?>
          <div class="form-item-group">
            <div class="form-item">
              <p class="form-item-title">banner<?php echo $i; ?>：</p>
              <div class="form-item-content">
                <input
                  type="text"
                  class="input-inner"
                  name="small-banner-<?php echo $i; ?>"
                  value="<?php echo $xm_options['banner']['small_banner']['banner'. $i]['path']; ?>"
                >
                <input type="button" name="img-upload" value="选择文件" class="choose-image">
                <p>
                  <img src="<?php echo $xm_options['banner']['small_banner']['banner'. $i]['path']; ?>" class="preview-img">
                </p>
              </div>
            </div>
            <div class="form-item">
              <p class="form-item-title">banner<?php echo $i; ?>标题：</p>
              <div class="form-item-content">
                <input
                  type="text"
                  class="input-inner"
                  name="small-banner-text-<?php echo $i; ?>"
                  value="<?php echo $xm_options['banner']['small_banner']['banner'. $i]['text']; ?>"
                >
              </div>
            </div>
            <div class="form-item">
              <p class="form-item-title">banner<?php echo $i; ?>链接：</p>
              <div class="form-item-content">
                <input
                  type="text"
                  class="input-inner"
                  name="small-banner-link-<?php echo $i; ?>"
                  value="<?php echo $xm_options['banner']['small_banner']['banner'. $i]['link']; ?>"
                >
              </div>
            </div>
          </div>
  				<?php
  					}
  				?>
        </li>

        <!-- 内容四 社交 -->
        <li class="content-item content4">
          <div class="form-item">
            <p class="form-item-title">打赏欢迎语：</p>
            <div class="form-item-content">
              <input
                type="text"
                class="input-inner"
                name="reward-text"
                value="<?php echo $xm_options['reward_text']; ?>"
              >
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">支付宝收账二维码：</p>
            <div class="form-item-content">
              <input
                type="text"
                class="input-inner"
                name="alipay"
                value="<?php echo $xm_options['alipay']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件" class="choose-image">
              <p>
                <img src="<?php echo $xm_options['alipay']; ?>" class="preview-img">
              </p>
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">微信收账二维码：</p>
            <div class="form-item-content">
              <input
                type="text"
                class="input-inner"
                name="wechatpay"
                value="<?php echo $xm_options['wechatpay']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件" class="choose-image">
              <p>
                <img src="<?php echo $xm_options['wechatpay']; ?>" class="preview-img">
              </p>
            </div>
          </div>

          <div class="form-item">
            <p class="form-item-title">友情链接：</p>
            <div class="form-item-content">
              <textarea class="input-inner" name="link" rows="15" cols="100"><?php echo $xm_options['link']; ?></textarea>
            </div>
          </div>
        </li>
        <!-- 内容五 自定义代码 -->
        <li class="content-item content5">
          <div class="form-item">
            <p class="form-item-title">后台登录页面css（不需要style标签）：</p>
            <div class="form-item-content">
              <textarea class="input-inner" name="login-css" rows="8" cols="100"><?php echo $xm_options['login_css']; ?></textarea>
            </div>
          </div>
          <div class="form-item">
            <p class="form-item-title">文章详情页css（不需要style标签）：</p>
            <div class="form-item-content">
              <textarea class="input-inner" name="details-css" rows="8" cols="100"><?php echo $xm_options['details_css']; ?></textarea>
            </div>
          </div>
        </li>
      </ul>
      <div class="btn-wrap">
        <input type="submit" class="btn-submit" name="btn-admin-options" value="保存更改">
      </div>
    </form>
  </div>
  <script src="<?php bloginfo('template_url'); ?>/include/js/set.js"></script>
<?php
	}
	function themeoptions_update() {
		// 数据提交
    $options                   = array(
      'update_themeoptions'    => 'true',
      'login_logo'             => $_POST['login-logo'],
      'aside_count'            => $_POST['aside-count'],
      'is_open_comment_upload' => $_POST['open-comment-upload'],
      'text_pic'               => $_POST['is-open-text-picture'],
      'logo'                   => $_POST['logo'],
      'thumbnail'              => $_POST['thumbnail-img'],
      'domain'                 => $_POST['domain'],
      'sidebar_notice'         => $_POST['sidebar-notice'],
      'footer_copyright'       => $_POST['footer-copyright'],
      'login_css'              => $_POST['login-css'],
      'details_css'            => $_POST['details-css'],
      'keywords'               => $_POST['keywords'],
      'description'            => $_POST['description'],
      'link'                   => $_POST['link'],
      'vip_style'              => $_POST['vip-style'],
      'reward_text'            => $_POST['reward-text'],
      'alipay'                 => $_POST['alipay'],
      'wechatpay'              => $_POST['wechatpay'],
			'banner'                 => array(
				'big_banner'           => array(
					'path'               => $_POST['big-banner'],
					'text'               => $_POST['big-banner-text'],
					'link'               => $_POST['big-banner-link'],
				),
				'small_banner'         => array(
					'banner1'            => array(
						'path'             => $_POST['small-banner-1'],
						'text'             => $_POST['small-banner-text-1'],
						'link'             => $_POST['small-banner-link-1'],
					),
					'banner2'            => array(
						'path'             => $_POST['small-banner-2'],
						'text'             => $_POST['small-banner-text-2'],
						'link'             => $_POST['small-banner-link-2'],
					),
					'banner3'            => array(
						'path'             => $_POST['small-banner-3'],
						'text'             => $_POST['small-banner-text-3'],
						'link'             => $_POST['small-banner-link-3'],
					)
				)
			)
    );
    update_option('xm_vue_options', stripslashes_deep($options));
	}
	add_action('admin_menu', 'themeoptions_admin_menu');
?>
