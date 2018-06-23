<?php
  header('Access-Control-Allow-Origin:*');
  header('Content-type:text/json');
  $array = array(
    'weixiao'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/weixiao.gif',
      'title'   => '微笑'
    ),
    'nanguo'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/nanguo.gif',
      'title'   => '难过'
    ),
    'qiudale'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/qiudale.gif',
      'title'   => '糗大了'
    ),
    'penxue'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/penxue.gif',
      'title'   => '喷血'
    ),
    'piezui'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/piezui.gif',
      'title'   => '撇嘴'
    ),
    'aoman'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/aoman.gif',
      'title'   => '傲慢'
    ),
    'baiyan'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/baiyan.gif',
      'title'   => '白眼'
    ),
    'bishi'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/bishi.gif',
      'title'   => '鄙视'
    ),
    'bizui'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/bizui.gif',
      'title'   => '闭嘴'
    ),
    'ciya'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/ciya.gif',
      'title'   => '呲牙'
    ),
    'dabing'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/dabing.gif',
      'title'   => '大兵'
    ),
    'daku'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/daku.gif',
      'title'   => '大哭'
    ),
    'deyi'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/deyi.gif',
      'title'   => '得意'
    ),
    'doge'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/doge.gif',
      'title'   => 'doge'
    ),
    'fadai'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/fadai.gif',
      'title'   => '发呆'
    ),
    'fanu'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/fanu.gif',
      'title'   => '发怒'
    ),
    'fendou'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/fendou.gif',
      'title'   => '奋斗'
    ),
    'ganga'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/ganga.gif',
      'title'   => '尴尬'
    ),
    'guzhang'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/guzhang.gif',
      'title'   => '鼓掌'
    ),
    'haixiu'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/haixiu.gif',
      'title'   => '害羞'
    ),
    'hanxiao'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/hanxiao.gif',
      'title'   => '憨笑'
    ),
    'haqian'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/haqian.gif',
      'title'   => '哈欠'
    ),
    'huaixiao'  => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/huaixiao.gif',
      'title'   => '坏笑'
    ),
    'jie'       => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/jie.gif',
      'title'   => '饥饿'
    ),
    'jingkong'  => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/jingkong.gif',
      'title'   => '惊恐'
    ),
    'jingxi'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/jingxi.gif',
      'title'   => '惊喜'
    ),
    'jingya'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/jingya.gif',
      'title'   => '惊讶'
    ),
    'keai'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/keai.gif',
      'title'   => '可爱'
    ),
    'kelian'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/kelian.gif',
      'title'   => '可怜'
    ),
    'koubi'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/koubi.gif',
      'title'   => '抠鼻'
    ),
    'ku'        => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/ku.gif',
      'title'   => '酷'
    ),
    'kuaikule'  => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/kuaikule.gif',
      'title'   => '快哭了'
    ),
    'kulou'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/kulou.gif',
      'title'   => '骷髅'
    ),
    'kun'       => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/kun.gif',
      'title'   => '困'
    ),
    'leiben'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/leiben.gif',
      'title'   => '泪奔'
    ),
    'lenghan'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/lenghan.gif',
      'title'   => '冷汗'
    ),
    'liuhan'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/liuhan.gif',
      'title'   => '流汗'
    ),
    'liulei'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/liulei.gif',
      'title'   => '流泪'
    ),
    'qiaoda'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/qiaoda.gif',
      'title'   => '敲打'
    ),
    'qinqin'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/qinqin.gif',
      'title'   => '亲亲'
    ),
    'saorao'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/saorao.gif',
      'title'   => '骚扰'
    ),
    'shuai'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/shuai.gif',
      'title'   => '衰'
    ),
    'shui'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/shui.gif',
      'title'   => '睡'
    ),
    'tiaopi'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/tiaopi.gif',
      'title'   => '调皮'
    ),
    'touxiao'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/touxiao.gif',
      'title'   => '偷笑'
    ),
    'tu'        => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/tu.gif',
      'title'   => '吐'
    ),
    'tuosai'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/tuosai.gif',
      'title'   => '托腮'
    ),
    'weiqu'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/weiqu.gif',
      'title'   => '委屈'
    ),
    'wozuimei'  => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/wozuimei.gif',
      'title'   => '我最美'
    ),
    'wunai'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/wunai.gif',
      'title'   => '无奈'
    ),
    'xia'       => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/xia.gif',
      'title'   => '吓'
    ),
    'xiaojiujie'=> array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/xiaojiujie.gif',
      'title'   => '小纠结'
    ),
    'xiaoku'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/xiaoku.gif',
      'title'   => '笑哭'
    ),
    'xieyanxiao'=> array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/xieyanxiao.gif',
      'title'   => '斜眼笑'
    ),
    'xu'        => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/xu.gif',
      'title'   => '嘘'
    ),
    'yinxian'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/yinxian.gif',
      'title'   => '阴险'
    ),
    'yiwen'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/yiwen.gif',
      'title'   => '疑问'
    ),
    'zuohengheng'=> array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/zuohengheng.gif',
      'title'   => '左哼哼'
    ),
    'youhengheng'=> array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/youhengheng.gif',
      'title'   => '右哼哼'
    ),
    'yun'       => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/yun.gif',
      'title'   => '晕'
    ),
    'zaijian'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/zaijian.gif',
      'title'   => '再见'
    ),
    'zhayanjian'=> array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/zhayanjian.gif',
      'title'   => '眨眼睛'
    ),
    'zhemo'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/zhemo.gif',
      'title'   => '折磨'
    ),
    'zhouma'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/zhouma.gif',
      'title'   => '咒骂'
    ),
    'zhuakuang' => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/zhuakuang.gif',
      'title'   => '抓狂'
    ),
    'aini'      => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/aini.gif',
      'title'   => '爱你'
    ),
    'baoquan'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/baoquan.gif',
      'title'   => '抱拳'
    ),
    'gouyin'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/gouyin.gif',
      'title'   => '勾引'
    ),
    'qiang'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/qiang.gif',
      'title'   => '强'
    ),
    'OK'        => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/OK.gif',
      'title'   => 'OK'
    ),
    'quantou'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/quantou.gif',
      'title'   => '拳头'
    ),
    'shengli'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/shengli.gif',
      'title'   => '胜利'
    ),
    'aixin'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/aixin.gif',
      'title'   => '爱心'
    ),
    'bangbangtang'=> array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/bangbangtang.gif',
      'title'   => '棒棒糖'
    ),
    'baojin'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/baojin.gif',
      'title'   => '爆筋'
    ),
    'caidao'    => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/caidao.gif',
      'title'   => '菜刀'
    ),
    'chi'       => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/chi.gif',
      'title'   => '吃'
    ),
    'dan'       => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/dan.gif',
      'title'   => '蛋'
    ),
    'haobang'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/haobang.gif',
      'title'   => '好棒'
    ),
    'hecai'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/hecai.gif',
      'title'   => '喝彩'
    ),
    'hexie'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/hexie.gif',
      'title'   => '河蟹'
    ),
    'juhua'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/juhua.gif',
      'title'   => '菊花'
    ),
    'pijiu'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/pijiu.gif',
      'title'   => '啤酒'
    ),
    'shouqiang' => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/shouqiang.gif',
      'title'   => '手枪'
    ),
    'xiaoyanger'=> array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/xiaoyanger.gif',
      'title'   => '小样儿'
    ),
    'xigua'     => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/xigua.gif',
      'title'   => '西瓜'
    ),
    'yangtuo'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/yangtuo.gif',
      'title'   => '羊驼'
    ),
    'youling'   => array(
      'url'     => 'https://upyun.xuanmo.xin/images/smilies/youling.gif',
      'title'   => '幽灵'
    )
  );
  echo urldecode(json_encode($array));
?>
