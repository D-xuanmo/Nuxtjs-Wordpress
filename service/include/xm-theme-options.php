<?php
function themeoptions_admin_menu()
{
    // 在控制面板的侧边栏添加设置选项页链接
    add_theme_page('xuan主题设置', 'xuan主题设置', 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

if (isset($_POST['update_themeoptions']) && $_POST['update_themeoptions'] == 'true') {
    themeoptions_update();
}

function themeoptions_page()
{
    // 获取提交的数据
    $xm_options = get_option('xm_vue_options');
    //加载上传图片的js(wp自带)
    wp_enqueue_script('thickbox');
    //加载css(wp自带)
    wp_enqueue_style('thickbox'); ?>
  	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/include/css/set.css">
  	<div class="container">
	    <h2 class="title">主题设置</h2>
	    <ul class="tab-wrapper">
			<li class="item is-active">基本</li>
			<li class="item">SEO</li>
            <li class="item">Banner</li>
			<li class="item">其他图片</li>
			<li class="item">打赏</li>
			<li class="item">社交</li>
			<li class="item">自定义代码</li>
			<li class="line"></li>
	    </ul>
    	<form method="post" action="">
			<input type="hidden" name="update_themeoptions" value="true">
			<ul class="content-wrapper">
		        <!-- 基本设置开始 -->
		        <li class="content-item">
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
							<span>示例<sup>必须加上http或者https协议</sup>：https://www.xuanmo.xin</span>
						</div>
					</div>

		          	<div class="form-item">
			            <p class="form-item-title">评论区vip等级样式：</p>
			            <div class="form-item-content">
			              	<label for="vip-style-1" class="vip-style" style="display: inline-block; width: 15px; height: 15px; background: url(<?php bloginfo('template_url'); ?>/static/images/vip.png) -147px -70px;"></label>
			              	<input
				                type="radio"
				                id="vip-style-1"
				                name="vip-style"
				                value="vip-style-1"
								<?php
								if ($xm_options['vip_style'] == 'vip-style-1' || $xm_options['vip_style'] == '') {
							        echo 'checked';
							    }
								?>
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
				                value="1"
				                <?php if ((bool) $xm_options['aside_count']) { echo 'checked'; } ?>
			              	>
			              	<label for="aside-count-off">关</label>
			              	<input
				                type="radio"
				                id="aside-count-off"
				                name="aside-count"
				                value="0"
				                <?php
								if ((bool) !$xm_options['aside_count'] || !$xm_options['aside_count']) {
							        echo 'checked';
							    }
								?>
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
				                value="1"
				                <?php
								if ((bool) $xm_options['is_open_comment_upload']) {
							        echo 'checked';
							    }
								?>
			              	>
			              	<label for="comment-upload-off">关</label>
			              	<input
				                type="radio"
				                id="comment-upload-off"
				                name="open-comment-upload"
				                value="0"
				                <?php
								if ((bool) !$xm_options['is_open_comment_upload'] || !$xm_options['is_open_comment_upload']) {
							        echo 'checked';
							    }
								?>
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
				                value="1"
				                <?php
								if ((bool) $xm_options['text_pic']) {
							        echo 'checked';
							    }
								?>
			              	>
			              	<label for="open-text-picture-off">关</label>
			              	<input
				                type="radio"
				                id="open-text-picture-off"
				                name="is-open-text-picture"
				                value="0"
				                <?php
								if ((bool) !$xm_options['text_pic'] || !$xm_options['text_pic']) {
							        echo 'checked';
							    }
								?>
			              	>
			            </div>
		          	</div>

		          	<div class="form-item">
			            <p class="form-item-title">是否开启文章自动摘要：</p>
			            <div class="form-item-content">
			              	<div class="input-inner">
				                <label for="open-article-auto-summary-on">开</label>
				                <input
				                  	type="radio"
				                  	id="open-article-auto-summary-on"
				                  	name="is-open-article-auto-summary"
				                  	value="1"
				                  	<?php
									if ((bool) $xm_options['article_auto_summary']) {
								        echo 'checked';
								    }
									?>
				                >
				                <label for="open-article-auto-summary-off-off">关</label>
				                <input
				                  	type="radio"
				                  	id="open-article-auto-summary-off"
				                  	name="is-open-article-auto-summary"
				                  	value="0"
				                  	<?php
									if ((bool) !$xm_options['article_auto_summary'] || !$xm_options['article_auto_summary']) {
								        echo 'checked';
								    }
									?>
				                >
			              </div>
			              <span>文章发布时如果不设置摘要，默认摘取文字正文的前160字</span>
			            </div>
		          	</div>

		          	<div class="form-item">
			            <p class="form-item-title">是否开启文章版权描述：</p>
			            <div class="form-item-content">
			              	<label for="open-article-copyright-on">开</label>
			              	<input
				                type="radio"
				                id="open-article-copyright-on"
				                name="is-open-article-copyright"
				                value="1"
			                	<?php
								if ((bool) $xm_options['article_copyright']) {
							        echo 'checked';
							    }
								?>
			              	>
			              	<label for="open-article-copyright-off-off">关</label>
			              	<input
				                type="radio"
				                id="open-article-copyright-off"
				                name="is-open-article-copyright"
				                value="0"
				                <?php
								if ((bool) !$xm_options['article_copyright'] || !$xm_options['article_copyright']) {
							        echo 'checked';
							    }
								?>
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
        		<!-- 基本设置结束 -->
        		<!-- SEO开始 -->
		        <li class="content-item">
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
		        <!-- SEO结束 -->
                <!-- Banner开始 -->
                <li class="content-item">
		            <div class="form-item">
                        <p class="form-item-title">Banner样式：</p>
			            <div class="form-item-content">
			              	<div class="input-inner">
                                <label for="banner-style-1" class="vip-style" style="display: inline-block; width: 200px; height: 90px; background: url(<?php bloginfo('template_url'); ?>/static/images/banner-style-1.png) no-repeat 0 0/100% 100%;"></label>
    			              	<input
    				                type="radio"
    				                id="banner-style-1"
    				                name="banner-style"
    				                value="1"
    								<?php
    								if ($xm_options['banner']['style'] == '1' || $xm_options['banner']['style'] == '') {
    							        echo 'checked';
    							    }
    								?>
    			              	>
    			              	<label for="banner-style-2" class="vip-style" style="display: inline-block; width: 200px; height: 90px; background: url(<?php bloginfo('template_url'); ?>/static/images/banner-style-2.png) no-repeat 0 0/100% 100%;"></label>
    			              	<input
    				                type="radio"
    				                id="banner-style-2"
    				                name="banner-style"
    				                value="2"
    								<?php
    								if ($xm_options['banner']['style'] == '2') {
    							        echo 'checked';
    							    }
    								?>
    			              	>
                            </div>
                            <p>提示：大图片比列：900*300px，小图片比列：180px*100px</p>
			            </div>
                    </div>
	  				<?php
	                for ($i = 0; $i <= 3; $i++) {
	                ?>
	          		<div class="form-item-group">
			            <div class="form-item">
			              	<p class="form-item-title">banner<?php echo $i + 1; ?>：</p>
			              	<div class="form-item-content">
				                <input
				                  	type="text"
				                  	class="input-inner"
				                  	name="banner-<?php echo $i; ?>"
				                  	value="<?php echo $xm_options['banner']['list'][$i]['path']; ?>"
				                >
				                <input type="button" name="img-upload" value="选择文件" class="choose-image">
				                <p>
				                  	<img src="<?php echo $xm_options['banner']['list'][$i]['path']; ?>" class="preview-img">
				                </p>
			              	</div>
			            </div>
			            <div class="form-item">
			              	<p class="form-item-title">banner<?php echo $i + 1; ?>标题：</p>
			              	<div class="form-item-content">
			                	<input
			                  		type="text"
			                  		class="input-inner"
			                  		name="banner-text-<?php echo $i; ?>"
			                  		value="<?php echo $xm_options['banner']['list'][$i]['text']; ?>"
			                	>
			              	</div>
			            </div>
			            <div class="form-item">
			              	<p class="form-item-title">banner<?php echo $i + 1; ?>链接：</p>
			              	<div class="form-item-content">
			                	<input
			                  		type="text"
			                  		class="input-inner"
			                  		name="banner-link-<?php echo $i; ?>"
			                  		value="<?php echo $xm_options['banner']['list'][$i]['link']; ?>"
			                	>
			              	</div>
			            </div>
	          		</div>
	  				<?php
	                }
					?>
                </li>
                <!-- Banner结束 -->
  				<!-- 其他设置开始 -->
        		<li class="content-item">
		          	<div class="form-item">
			            <p class="form-item-title">前端标签页logo：</p>
			            <div class="form-item-content">
			              	<input
				                type="text"
				                class="input-inner"
				                name="favicon"
				                value="<?php echo $xm_options['favicon']; ?>"
			              	>
			              	<input type="button" name="img-upload" value="选择文件" class="choose-image">
                            <span>比列最好为32px*32px</span>
			              	<p>
			                	<img src="<?php echo $xm_options['favicon']; ?>" class="preview-img">
			              	</p>
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
				                value="<?php echo $xm_options['thumbnail']; ?>"
							>
			              	<input type="button" name="img-upload" value="选择文件" class="choose-image">
			              	<p>
			                	<img src="<?php echo $xm_options['thumbnail']; ?>" class="preview-img">
			              	</p>
			            </div>
		          	</div>
        		</li>
		        <!-- 其他设置结束 -->
		        <!-- 打赏开始 -->
        		<li class="content-item">
		          	<div class="form-item">
			            <p class="form-item-title">是否开启打赏：</p>
			            <div class="form-item-content">
			              	<label for="is-open-reward-on">开</label>
			              	<input
			                	type="radio"
			                	id="is-open-reward-on"
			                	name="is-open-reward"
			                	value="1"
								<?php if ((bool) $xm_options['is_open_reward']) {
			                        echo 'checked';
			                    } ?>
			              	>
			              	<label for="is-open-reward-off">关</label>
			              	<input
			                	type="radio"
			                	id="is-open-reward-off"
			                	name="is-open-reward"
			                	value="0" <?php if ((bool) !$xm_options['is_open_reward'] || !$xm_options['is_open_reward']) {
			                        echo 'checked';
			                    } ?>
			              	>
			            </div>
		          	</div>

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
		        </li>
		        <!-- 打赏结束 -->
		        <!-- 友情链接开始 -->
		        <li class="content-item">
		          	<div class="form-item">
		            	<p class="form-item-title">友情链接：</p>
		            	<div class="form-item-content">
		              		<textarea class="input-inner friendship-link" name="link" rows="15" cols="100"><?php echo $xm_options['link']; ?></textarea>
		            	</div>
		          	</div>
		        </li>
		        <!-- 友情链接结束 -->
		        <!-- 自定义代码开始 -->
        		<li class="content-item">
		          	<div class="form-item">
		            	<p class="form-item-title">后台登录页面css<br/>(不需要style标签):</p>
		            	<div class="form-item-content">
		              		<textarea class="input-inner" name="login-css" rows="8" cols="100"><?php echo $xm_options['login_css']; ?></textarea>
		            	</div>
		          	</div>
		          	<div class="form-item">
			            <p class="form-item-title">全局公用css<br/>(不需要style标签):</p>
			            <div class="form-item-content">
			              	<textarea class="input-inner" name="global-css" rows="8" cols="100"><?php echo $xm_options['global_css']; ?></textarea>
			            </div>
		          	</div>
		          	<div class="form-item">
			            <p class="form-item-title">文章详情页css<br/>(不需要style标签):</p>
			            <div class="form-item-content">
			              	<textarea class="input-inner" name="details-css" rows="8" cols="100"><?php echo $xm_options['details_css']; ?></textarea>
			            </div>
		          	</div>
        		</li>
        		<!-- 自定义代码结束 -->
      		</ul>
      		<div class="btn-wrap">
        		<input type="submit" class="btn-submit" name="btn-admin-options" value="保存更改">
      		</div>
    	</form>
  	</div>
  	<script src="<?php bloginfo('template_url'); ?>/include/js/set.js"></script>
<?php
}
    function themeoptions_update()
    {
        // 数据提交
        $options                   = array(
			'update_themeoptions'    => 'true',
			'favicon'                => $_POST['favicon'],
			'login_logo'             => $_POST['login-logo'],
			'aside_count'            => $_POST['aside-count'],
			'is_open_comment_upload' => $_POST['open-comment-upload'],
			'text_pic'               => $_POST['is-open-text-picture'],
			'article_auto_summary'   => $_POST['is-open-article-auto-summary'],
			'article_copyright'      => $_POST['is-open-article-copyright'],
			'logo'                   => $_POST['logo'],
			'thumbnail'              => $_POST['thumbnail-img'],
			'domain'                 => $_POST['domain'],
			'sidebar_notice'         => $_POST['sidebar-notice'],
			'footer_copyright'       => $_POST['footer-copyright'],
			'login_css'              => $_POST['login-css'],
			'details_css'            => $_POST['details-css'],
			'global_css'             => $_POST['global-css'],
			'keywords'               => $_POST['keywords'],
			'description'            => $_POST['description'],
			'link'                   => $_POST['link'],
			'vip_style'              => $_POST['vip-style'],
			'reward_text'            => $_POST['reward-text'],
			'is_open_reward'         => $_POST['is-open-reward'],
			'alipay'                 => $_POST['alipay'],
			'wechatpay'              => $_POST['wechatpay'],
	        'banner'                 => array(
                'style'              => $_POST['banner-style'],
                'list'               => array(
                    array(
                        'path'             => $_POST['banner-0'],
                        'text'             => $_POST['banner-text-0'],
                        'link'             => $_POST['banner-link-0'],
                    ),
                    array(
	                    'path'             => $_POST['banner-1'],
	                    'text'             => $_POST['banner-text-1'],
	                    'link'             => $_POST['banner-link-1'],
	                ),
                    array(
	                    'path'             => $_POST['banner-2'],
	                    'text'             => $_POST['banner-text-2'],
	                    'link'             => $_POST['banner-link-2'],
	                ),
                    array(
	                    'path'             => $_POST['banner-3'],
	                    'text'             => $_POST['banner-text-3'],
	                    'link'             => $_POST['banner-link-3'],
	                )
                )
	        )
	    );
        update_option('xm_vue_options', stripslashes_deep($options));
    }
    add_action('admin_menu', 'themeoptions_admin_menu');
?>
