<?php
function themeoptions_admin_menu() {
	// 在控制面板的侧边栏添加设置选项页链接
	add_theme_page('xuan主题设置', 'xuan主题设置','edit_themes', basename(__FILE__), 'themeoptions_page');
}
if ( isset($_POST['update_themeoptions']) && $_POST['update_themeoptions'] == 'true' ) themeoptions_update();
function themeoptions_page() {
  // 获取提交的数据
  $a_options = get_option('xm_vue_options');
  //加载上传图片的js(wp自带)
  wp_enqueue_script('thickbox');
  //加载css(wp自带)
  wp_enqueue_style('thickbox');
?>
  <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/include/css/set.css">
  <div class="wrap">
    <h2>主题设置</h2>
    <ul class="nav-wrap clearfix">
      <li class="nav-list on">基本</li>
      <li class="nav-list">SEO</li>
      <li class="nav-list">图片</li>
      <li class="nav-list">社交</li>
      <li class="nav-list">自定义代码</li>
    </ul>
    <form method="post" action="">
      <input type="hidden" name="update_themeoptions" value="true">
      <!-- 内容一 基本 -->
      <div class="content-wrap content1">
        <div class="row clearfix">
          <label class="fl left-wrap">侧边栏统计功能：</label>
          <div class="fr right-wrap">
            <label for="aside-count-on">开</label>
            <input
              type="radio"
              id="aside-count-on"
              name="aside-count"
              value="on" <?php if($a_options['aside_count'] == 'on') echo 'checked'; ?>
            >
            <label for="aside-count-off">关</label>
            <input
              type="radio"
              id="aside-count-off"
              name="aside-count"
              value="off" <?php if($a_options['aside_count'] == 'off' || $a_options['aside_count'] == '') echo 'checked'; ?>
            >
          </div>
        </div>
        <div class="row clearfix">
          <label class="fl left-wrap">是否开启文字头像：</label>
          <div class="fr right-wrap">
            <label for="text-pic-on">开</label>
            <input
              type="radio"
              id="text-pic-on"
              name="text-pic"
              value="on" <?php if($a_options['text_pic'] == 'on') echo 'checked'; ?>
            >
            <label for="text-pic-off">关</label>
            <input
              type="radio"
              id="text-pic-off"
              name="text-pic"
              value="off" <?php if($a_options['text_pic'] == 'off' || $a_options['text_pic'] == '') echo 'checked'; ?>
            >
          </div>
        </div>

        <div class="row clearfix">
          <label for="domain" class="fl left-wrap">站点前端域名：</label>
          <div class="fr right-wrap">
						<input
							type="text"
							class="url-inp"
							name="domain"
							id="domain"
							value="<?php echo $a_options['domain']; ?>"
						>
          </div>
        </div>

        <div class="row clearfix">
          <label for="sidebar-notice" class="fl left-wrap">侧边栏公告：</label>
          <div class="fr right-wrap">
            <textarea id="sidebar-notice" name="sidebar-notice" rows="15" cols="100"><?php echo $a_options['sidebar_notice']; ?></textarea>
          </div>
        </div>

        <div class="row clearfix">
          <label for="footer-copyright" class="fl left-wrap">底部版权文字：</label>
          <div class="fr right-wrap">
            <textarea id="footer-copyright" name="footer-copyright" rows="15" cols="100"><?php echo $a_options['footer_copyright']; ?></textarea>
          </div>
        </div>
      </div>
      <!-- 内容二 SEO -->
      <div class="content-wrap content2">
        <div class="row clearfix">
          <label for="keywords" class="fl left-wrap">首页关键词(keywords)：</label>
          <div class="fr right-wrap">
            <textarea id="keywords" name="keywords" rows="8" cols="100"><?php echo $a_options['keywords'] ?></textarea>
          </div>
        </div>
        <div class="row clearfix">
          <label for="description class=" fl left-wrap"">首页描述(description)：</label>
          <div class="fr right-wrap">
            <textarea id="description" name="description" rows="8" cols="100"><?php echo $a_options['description'] ?></textarea>
          </div>
        </div>
      </div>
			<!-- 内容三 图片设置 -->
      <div class="content-wrap content3">
				<div class="row clearfix">
          <label class="fl left-wrap">评论区vip等级样式：</label>
          <div class="fr right-wrap">
            <label for="vip-style-1" class="vip-style" style="display: inline-block; width: 15px; height: 15px; background: url(<?php bloginfo('template_url'); ?>/static/images/vip.png) -147px -70px;"></label>
            <input
              type="radio"
              id="vip-style-1"
              name="vip-style"
              value="vip-style-1" <?php if($a_options['vip_style'] == 'vip-style-1' || $a_options['vip_style'] == '') echo 'checked'; ?>
            >
          </div>
        </div>
				<div class="row">
          <div class="margin-top-15 clearfix">
            <label class="fl left-wrap" for="">后台登录logo：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
                name="login-logo"
                id="login-logo"
                value="<?php echo $a_options['login_logo']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件">
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <div class="fl left-wrap">
              后台登录图标预览：
            </div>
            <div class="fr right-wrap">
              <img src="<?php echo $a_options['login_logo']; ?>" class="preview-img" style="max-width: 100px;" alt="">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="margin-top-15 clearfix">
            <label class="fl left-wrap" for="">导航logo：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
                name="logo"
                id="logo"
                value="<?php echo $a_options['logo']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件">
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <div class="fl left-wrap">
              导航logo预览：
            </div>
            <div class="fr right-wrap">
              <img src="<?php echo $a_options['logo']; ?>" class="preview-img" style="max-width: 100px;" alt="">
            </div>
          </div>
        </div>

				<div class="row">
          <div class="margin-top-15 clearfix">
						<label class="fl left-wrap" for="">默认缩略图：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
								name="thumbnail-img"
                id="thumbnail-img"
                value="<?php echo $a_options['thumbnail']; ?>">
              <input type="button" name="img-upload" value="选择文件">
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <div class="fl left-wrap">
              默认缩略图预览：
            </div>
            <div class="fr right-wrap">
              <img src="<?php echo $a_options['thumbnail']; ?>" class="preview-img" style="max-width: 100px;" alt="">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="margin-top-15 clearfix">
            <label class="fl left-wrap" for="">banner大图：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
                name="big-banner"
                id="big-banner"
                value="<?php echo $a_options['banner']['big_banner']['path']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件">
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <label class="fl left-wrap" for="">banner标题：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
                name="big-banner-text"
                id="big-banner-text"
                value="<?php echo $a_options['banner']['big_banner']['text']; ?>"
              >
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <label class="fl left-wrap" for="">banner链接：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
                name="big-banner-link"
                id="big-banner-link"
                value="<?php echo $a_options['banner']['big_banner']['link']; ?>"
              >
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <div class="fl left-wrap">
              banner大图预览：
            </div>
            <div class="fr right-wrap">
              <img src="<?php echo $a_options['banner']['big_banner']['path']; ?>" class="preview-img" style="max-width: 400px; max-height: 200px;" alt="">
            </div>
          </div>
        </div>
				<?php
					for ($i = 1; $i < 4; $i++) {
				?>
					<div class="row">
	          <div class="margin-top-15 clearfix">
	            <label class="fl left-wrap" for="">banner<?php echo $i; ?>：</label>
	            <div class="fr right-wrap">
	              <input
	                type="text"
	                class="url-inp"
	                name="small-banner-<?php echo $i; ?>"
	                id="small-banner-<?php echo $i; ?>"
	                value="<?php echo $a_options['banner']['small_banner']['banner'. $i]['path']; ?>"
	              >
	              <input type="button" name="img-upload" value="选择文件">
	            </div>
	          </div>
	          <div class="margin-top-15 clearfix">
	            <label class="fl left-wrap" for="">banner<?php echo $i; ?>标题：</label>
	            <div class="fr right-wrap">
	              <input
	                type="text"
	                class="url-inp"
	                name="small-banner-text-<?php echo $i; ?>"
	                id="small-banner-text-<?php echo $i; ?>"
	                value="<?php echo $a_options['banner']['small_banner']['banner'. $i]['text']; ?>"
	              >
	            </div>
	          </div>
	          <div class="margin-top-15 clearfix">
	            <label class="fl left-wrap" for="">banner<?php echo $i; ?>链接：</label>
	            <div class="fr right-wrap">
	              <input
	                type="text"
	                class="url-inp"
	                name="small-banner-link-<?php echo $i; ?>"
	                id="small-banner-link-<?php echo $i; ?>"
	                value="<?php echo $a_options['banner']['small_banner']['banner'. $i]['link']; ?>"
	              >
	            </div>
	          </div>
	          <div class="margin-top-15 clearfix">
	            <div class="fl left-wrap">
	              banner<?php echo $i; ?>大图预览：
	            </div>
	            <div class="fr right-wrap">
	              <img src="<?php echo $a_options['banner']['small_banner']['banner'. $i]['path']; ?>" class="preview-img" style="max-width: 400px; max-height: 200px;" alt="">
	            </div>
	          </div>
	        </div>
				<?php
					}
				?>

      </div>
      <!-- 内容四 社交 -->
      <div class="content-wrap content4">
				<div class="row clearfix">
          <label for="reward-text" class="fl left-wrap">打赏欢迎语：</label>
          <div class="fr right-wrap">
						<input
							type="text"
							class="url-inp"
							name="reward-text"
							id="reward-text"
							value="<?php echo $a_options['reward_text']; ?>"
						>
          </div>
        </div>

				<div class="row">
          <div class="margin-top-15 clearfix">
            <label class="fl left-wrap" for="">支付宝收账二维码：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
                name="alipay"
                id="alipay"
                value="<?php echo $a_options['alipay']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件">
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <div class="fl left-wrap">
              收账二维码预览：
            </div>
            <div class="fr right-wrap">
              <img src="<?php echo $a_options['alipay']; ?>" class="preview-img" style="max-width: 100px;" alt="">
            </div>
          </div>
        </div>

				<!-- 微信付款二维码 -->
				<div class="row">
					<!-- 支付宝付款二维码 -->
          <div class="margin-top-15 clearfix">
            <label class="fl left-wrap" for="">微信收账二维码：</label>
            <div class="fr right-wrap">
              <input
                type="text"
                class="url-inp"
                name="wechatpay"
                id="wechatpay"
                value="<?php echo $a_options['wechatpay']; ?>"
              >
              <input type="button" name="img-upload" value="选择文件">
            </div>
          </div>
          <div class="margin-top-15 clearfix">
            <div class="fl left-wrap">
              收账二维码预览：
            </div>
            <div class="fr right-wrap">
              <img src="<?php echo $a_options['wechatpay']; ?>" class="preview-img" style="max-width: 100px;" alt="">
            </div>
          </div>
        </div>

        <div class="row clearfix">
          <label class="fl left-wrap" for="link">友情链接：</label>
          <div class="fr right-wrap">
            <textarea id="link" name="link" rows="15" cols="100"><?php echo $a_options['link']; ?></textarea>
          </div>
        </div>
      </div>
      <!-- 内容五 自定义代码 -->
      <div class="content-wrap content5">
        <div class="row clearfix">
          <label class="fl left-wrap" for="login-css">后台登录页面css（不需要style标签）：</label>
          <div class="fr right-wrap">
            <textarea id="login-css" name="login-css" rows="8" cols="100"><?php echo $a_options['login_css']; ?></textarea>
          </div>
        </div>
				<div class="row clearfix">
          <label class="fl left-wrap" for="details-css">文章详情页css（不需要style标签）：</label>
          <div class="fr right-wrap">
            <textarea id="details-css" name="details-css" rows="8" cols="100"><?php echo $a_options['details_css']; ?></textarea>
          </div>
        </div>
      </div>
      <div class="row btn-wrap">
        <input type="submit" class="submit-btn" name="bcn-admin-options" value="保存更改">
      </div>
    </form>
  </div>
  <script src="<?php bloginfo('template_url'); ?>/include/js/set.js"></script>
<?php
	}
	function themeoptions_update() {
		// 数据提交
    $options = array(
      'update_themeoptions' => 'true',
      'login_logo' => $_POST['login-logo'],
      'aside_count' => $_POST['aside-count'],
      'text_pic' => $_POST['text-pic'],
      'logo' => $_POST['logo'],
      'thumbnail' => $_POST['thumbnail-img'],
      'domain' => $_POST['domain'],
      'sidebar_notice' => $_POST['sidebar-notice'],
      'footer_copyright' => $_POST['footer-copyright'],
      'login_css'  => $_POST['login-css'],
      'details_css'  => $_POST['details-css'],
      'keywords' => $_POST['keywords'],
      'description' => $_POST['description'],
      'link' => $_POST['link'],
      'vip_style' => $_POST['vip-style'],
      'reward_text' => $_POST['reward-text'],
      'alipay' => $_POST['alipay'],
      'wechatpay' => $_POST['wechatpay'],
			'banner' => array(
				'big_banner' => array(
					'path' => $_POST['big-banner'],
					'text' => $_POST['big-banner-text'],
					'link' => $_POST['big-banner-link'],
				),
				'small_banner' => array(
					'banner1' => array(
						'path' => $_POST['small-banner-1'],
						'text' => $_POST['small-banner-text-1'],
						'link' => $_POST['small-banner-link-1'],
					),
					'banner2' => array(
						'path' => $_POST['small-banner-2'],
						'text' => $_POST['small-banner-text-2'],
						'link' => $_POST['small-banner-link-2'],
					),
					'banner3' => array(
						'path' => $_POST['small-banner-3'],
						'text' => $_POST['small-banner-text-3'],
						'link' => $_POST['small-banner-link-3'],
					)
				)
			)
    );
    update_option('xm_vue_options', stripslashes_deep($options));
	}
	add_action('admin_menu', 'themeoptions_admin_menu');
?>
