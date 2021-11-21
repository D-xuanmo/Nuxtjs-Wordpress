<?php
// 添加自定义编辑器按钮
function add_my_media_button() {
    echo '<span id="custom-html-transform" class="button">html尖括号转义</span><span id="custom-inser-code" class="button">插入代码</span>';
}

function appthemes_add_quicktags() {
    ?>
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/include/css/custom-editor.css">
    <script>
        var $ = jQuery;
        var aLanguage = ['html', 'css', 'sass', 'scss', 'less', 'javascript', 'typescript', 'tsx', 'json', 'php', 'sql', 'http', 'nginx', 'git', 'markdown', 'yaml'];
        if (typeof QTags !== 'undefined') {
            for (var i = 0, length = aLanguage.length; i < length; i++) {
                QTags.addButton(aLanguage[i], aLanguage[i], '\n<pre class="language-' + aLanguage[i] + ' line-numbers"><code class="language-' + aLanguage[i] + '">\n', '\n</code></pre>\n');
            }
            QTags.addButton('h2', 'h2', '<h2>', '</h2>');
            QTags.addButton('2-text', '2-text', '<span style="display:inline-block; width:28px;">', '</span>');
            QTags.addButton('star', 'star', '<i class="iconfont icon-star c-theme">', '</i>');
            QTags.addButton('arrow-right', 'arrow-right', '<i class="iconfont icon-arrow-right-f">', '</i>');
        }

        // 添加html转换容器
        $(function () {
            (function () {
                var $editor = $('<div id="custom-code-editor" style="display: none"></div>');
                var $editorContent = $('<div class="custom-code-editor--content" />');
                var $form = $('<form />');
                var $inner = $('<textarea class="custom-code-inner" />');
                var $htmlBtn = $('<button class="button button-primary button-large">HTML 转义</button>');
                var $copyBtn = $('<button class="button button-primary button-large">复制代码</button>');
                var $closeBtn = $('<button class="preview button">关闭</button>');
                var $footerBtns = $('<div class="custom-code-footer" />').append($htmlBtn).append($copyBtn).append($closeBtn);
                var $hideInput = $('<textarea class="custom-code-hide" />');
                aLanguage.forEach(function(item, index) {
                    var $radio = $('<input id="custom-radio-'+ item +'" name="custom-radio" type="radio" style="vertical-align: bottom" />');
                    var $label = $('<label for="custom-radio-'+ item +'" style="vertical-align: middle" />');
                    var $span = $('<span style="margin: 0 10px 10px 0" />');
                    if (index === 0) $radio.attr('checked', true);
                    $label.text(item);
                    $radio.val(item);
                    $span.append($radio).append($label).appendTo($form);
                });
                $htmlBtn.click(function () {
                    $inner.val(function () {
                        return $(this).val().replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    });
                });
                $copyBtn.click(function () {
                    var codeLanguage = $form.serializeArray()[0].value;
                    var code = '\n<pre class="language-'
                        + codeLanguage
                        + ' line-numbers"><code class="language-'
                        + codeLanguage
                        + '">\n'
                        + $inner.val()
                        + '\n</code></pre>\n';
                    console.log(code)
                    $hideInput.val(code);
                    $hideInput[0].select();
                    console.log($hideInput.val())
                    if (document.execCommand('Copy')) {
                        $(this).text('复制成功');
                        setTimeout(function () {
                            $copyBtn.text('复制');
                        }, 1500);
                    }
                });
                $closeBtn.click(function () {
                    $editor.hide();
                    $inner.val('');
                });
                $editorContent.click(function (event) {
                    event.stopPropagation();
                });
                $editorContent.append($hideInput).append($form).append($inner).append($footerBtns).appendTo($editor);
                $editor.click(function () { $closeBtn.click(); });
                $('body').append($editor);
                $('#custom-inser-code').click(function () { $editor.show(); });
            })();

            $('#custom-html-transform').click(function () {
                $('body').append(
                    '<div id="custom-html-transform-content">'
                    + '<textarea name="name" rows="15" cols="100"></textarea>'
                    + '<span id="xm-transfom-btn">转换</span>'
                    + '<span id="xm-copy-btn">复制</span>'
                    + '</div>'
                );
                $('#custom-html-transform-content')
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
                $('textarea').click(function (e) {
                    e.stopPropagation();
                });
                $('#xm-transfom-btn')
                    .css('transform', 'translateX(-115%)')
                    .click(function (e) {
                        e.stopPropagation();
                        $(this).siblings('textarea').val(function () {
                            return $(this).val().replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        });
                    });
                $('#xm-copy-btn').click(function (e) {
                    e.stopPropagation();
                    $(this).siblings('textarea')[0].select();
                    if (document.execCommand('Copy')) {
                        $(this).text('复制成功');
                    }
                });
                $('#custom-html-transform-content').click(function () {
                    $(this).remove();
                });
            });
        });
    </script>
    <?php
}

add_action('media_buttons', 'add_my_media_button');
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags');