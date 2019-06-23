<?php
  header('Access-Control-Allow-Origin:*');
  header('Content-type:text/json');
  $array = array(
    'weixiao'   => array(
      'url'     => '/images/smilies/weixiao.gif',
      'title'   => '微笑'
    ),
    'nanguo'    => array(
      'url'     => '/images/smilies/nanguo.gif',
      'title'   => '难过'
    ),
    'qiudale'   => array(
      'url'     => '/images/smilies/qiudale.gif',
      'title'   => '糗大了'
    ),
    'penxue'    => array(
      'url'     => '/images/smilies/penxue.gif',
      'title'   => '喷血'
    ),
    'piezui'    => array(
      'url'     => '/images/smilies/piezui.gif',
      'title'   => '撇嘴'
    ),
    'aoman'     => array(
      'url'     => '/images/smilies/aoman.gif',
      'title'   => '傲慢'
    ),
    'baiyan'    => array(
      'url'     => '/images/smilies/baiyan.gif',
      'title'   => '白眼'
    ),
    'bishi'     => array(
      'url'     => '/images/smilies/bishi.gif',
      'title'   => '鄙视'
    ),
    'bizui'     => array(
      'url'     => '/images/smilies/bizui.gif',
      'title'   => '闭嘴'
    ),
    'ciya'      => array(
      'url'     => '/images/smilies/ciya.gif',
      'title'   => '呲牙'
    ),
    'dabing'    => array(
      'url'     => '/images/smilies/dabing.gif',
      'title'   => '大兵'
    ),
    'daku'      => array(
      'url'     => '/images/smilies/daku.gif',
      'title'   => '大哭'
    ),
    'deyi'      => array(
      'url'     => '/images/smilies/deyi.gif',
      'title'   => '得意'
    ),
    'doge'      => array(
      'url'     => '/images/smilies/doge.gif',
      'title'   => 'doge'
    ),
    'fadai'     => array(
      'url'     => '/images/smilies/fadai.gif',
      'title'   => '发呆'
    ),
    'fanu'      => array(
      'url'     => '/images/smilies/fanu.gif',
      'title'   => '发怒'
    ),
    'fendou'    => array(
      'url'     => '/images/smilies/fendou.gif',
      'title'   => '奋斗'
    ),
    'ganga'     => array(
      'url'     => '/images/smilies/ganga.gif',
      'title'   => '尴尬'
    ),
    'guzhang'   => array(
      'url'     => '/images/smilies/guzhang.gif',
      'title'   => '鼓掌'
    ),
    'haixiu'    => array(
      'url'     => '/images/smilies/haixiu.gif',
      'title'   => '害羞'
    ),
    'hanxiao'   => array(
      'url'     => '/images/smilies/hanxiao.gif',
      'title'   => '憨笑'
    ),
    'haqian'    => array(
      'url'     => '/images/smilies/haqian.gif',
      'title'   => '哈欠'
    ),
    'huaixiao'  => array(
      'url'     => '/images/smilies/huaixiao.gif',
      'title'   => '坏笑'
    ),
    'jie'       => array(
      'url'     => '/images/smilies/jie.gif',
      'title'   => '饥饿'
    ),
    'jingkong'  => array(
      'url'     => '/images/smilies/jingkong.gif',
      'title'   => '惊恐'
    ),
    'jingxi'    => array(
      'url'     => '/images/smilies/jingxi.gif',
      'title'   => '惊喜'
    ),
    'jingya'    => array(
      'url'     => '/images/smilies/jingya.gif',
      'title'   => '惊讶'
    ),
    'keai'      => array(
      'url'     => '/images/smilies/keai.gif',
      'title'   => '可爱'
    ),
    'kelian'    => array(
      'url'     => '/images/smilies/kelian.gif',
      'title'   => '可怜'
    ),
    'koubi'     => array(
      'url'     => '/images/smilies/koubi.gif',
      'title'   => '抠鼻'
    ),
    'ku'        => array(
      'url'     => '/images/smilies/ku.gif',
      'title'   => '酷'
    ),
    'kuaikule'  => array(
      'url'     => '/images/smilies/kuaikule.gif',
      'title'   => '快哭了'
    ),
    'kulou'     => array(
      'url'     => '/images/smilies/kulou.gif',
      'title'   => '骷髅'
    ),
    'kun'       => array(
      'url'     => '/images/smilies/kun.gif',
      'title'   => '困'
    ),
    'leiben'    => array(
      'url'     => '/images/smilies/leiben.gif',
      'title'   => '泪奔'
    ),
    'lenghan'   => array(
      'url'     => '/images/smilies/lenghan.gif',
      'title'   => '冷汗'
    ),
    'liuhan'    => array(
      'url'     => '/images/smilies/liuhan.gif',
      'title'   => '流汗'
    ),
    'liulei'    => array(
      'url'     => '/images/smilies/liulei.gif',
      'title'   => '流泪'
    ),
    'qiaoda'    => array(
      'url'     => '/images/smilies/qiaoda.gif',
      'title'   => '敲打'
    ),
    'qinqin'    => array(
      'url'     => '/images/smilies/qinqin.gif',
      'title'   => '亲亲'
    ),
    'saorao'    => array(
      'url'     => '/images/smilies/saorao.gif',
      'title'   => '骚扰'
    ),
    'shuai'     => array(
      'url'     => '/images/smilies/shuai.gif',
      'title'   => '衰'
    ),
    'shui'      => array(
      'url'     => '/images/smilies/shui.gif',
      'title'   => '睡'
    ),
    'tiaopi'    => array(
      'url'     => '/images/smilies/tiaopi.gif',
      'title'   => '调皮'
    ),
    'touxiao'   => array(
      'url'     => '/images/smilies/touxiao.gif',
      'title'   => '偷笑'
    ),
    'tu'        => array(
      'url'     => '/images/smilies/tu.gif',
      'title'   => '吐'
    ),
    'tuosai'    => array(
      'url'     => '/images/smilies/tuosai.gif',
      'title'   => '托腮'
    ),
    'weiqu'     => array(
      'url'     => '/images/smilies/weiqu.gif',
      'title'   => '委屈'
    ),
    'wozuimei'  => array(
      'url'     => '/images/smilies/wozuimei.gif',
      'title'   => '我最美'
    ),
    'wunai'     => array(
      'url'     => '/images/smilies/wunai.gif',
      'title'   => '无奈'
    ),
    'xia'       => array(
      'url'     => '/images/smilies/xia.gif',
      'title'   => '吓'
    ),
    'xiaojiujie'=> array(
      'url'     => '/images/smilies/xiaojiujie.gif',
      'title'   => '小纠结'
    ),
    'xiaoku'   => array(
      'url'     => '/images/smilies/xiaoku.gif',
      'title'   => '笑哭'
    ),
    'xieyanxiao'=> array(
      'url'     => '/images/smilies/xieyanxiao.gif',
      'title'   => '斜眼笑'
    ),
    'xu'        => array(
      'url'     => '/images/smilies/xu.gif',
      'title'   => '嘘'
    ),
    'yinxian'   => array(
      'url'     => '/images/smilies/yinxian.gif',
      'title'   => '阴险'
    ),
    'yiwen'     => array(
      'url'     => '/images/smilies/yiwen.gif',
      'title'   => '疑问'
    ),
    'zuohengheng'=> array(
      'url'     => '/images/smilies/zuohengheng.gif',
      'title'   => '左哼哼'
    ),
    'youhengheng'=> array(
      'url'     => '/images/smilies/youhengheng.gif',
      'title'   => '右哼哼'
    ),
    'yun'       => array(
      'url'     => '/images/smilies/yun.gif',
      'title'   => '晕'
    ),
    'zaijian'   => array(
      'url'     => '/images/smilies/zaijian.gif',
      'title'   => '再见'
    ),
    'zhayanjian'=> array(
      'url'     => '/images/smilies/zhayanjian.gif',
      'title'   => '眨眼睛'
    ),
    'zhemo'     => array(
      'url'     => '/images/smilies/zhemo.gif',
      'title'   => '折磨'
    ),
    'zhouma'    => array(
      'url'     => '/images/smilies/zhouma.gif',
      'title'   => '咒骂'
    ),
    'zhuakuang' => array(
      'url'     => '/images/smilies/zhuakuang.gif',
      'title'   => '抓狂'
    ),
    'aini'      => array(
      'url'     => '/images/smilies/aini.gif',
      'title'   => '爱你'
    ),
    'baoquan'   => array(
      'url'     => '/images/smilies/baoquan.gif',
      'title'   => '抱拳'
    ),
    'gouyin'    => array(
      'url'     => '/images/smilies/gouyin.gif',
      'title'   => '勾引'
    ),
    'qiang'     => array(
      'url'     => '/images/smilies/qiang.gif',
      'title'   => '强'
    ),
    'OK'        => array(
      'url'     => '/images/smilies/OK.gif',
      'title'   => 'OK'
    ),
    'quantou'   => array(
      'url'     => '/images/smilies/quantou.gif',
      'title'   => '拳头'
    ),
    'shengli'   => array(
      'url'     => '/images/smilies/shengli.gif',
      'title'   => '胜利'
    ),
    'aixin'     => array(
      'url'     => '/images/smilies/aixin.gif',
      'title'   => '爱心'
    ),
    'bangbangtang'=> array(
      'url'     => '/images/smilies/bangbangtang.gif',
      'title'   => '棒棒糖'
    ),
    'baojin'    => array(
      'url'     => '/images/smilies/baojin.gif',
      'title'   => '爆筋'
    ),
    'caidao'    => array(
      'url'     => '/images/smilies/caidao.gif',
      'title'   => '菜刀'
    ),
    'chi'       => array(
      'url'     => '/images/smilies/chi.gif',
      'title'   => '吃'
    ),
    'dan'       => array(
      'url'     => '/images/smilies/dan.gif',
      'title'   => '蛋'
    ),
    'haobang'   => array(
      'url'     => '/images/smilies/haobang.gif',
      'title'   => '好棒'
    ),
    'hecai'     => array(
      'url'     => '/images/smilies/hecai.gif',
      'title'   => '喝彩'
    ),
    'hexie'     => array(
      'url'     => '/images/smilies/hexie.gif',
      'title'   => '河蟹'
    ),
    'juhua'     => array(
      'url'     => '/images/smilies/juhua.gif',
      'title'   => '菊花'
    ),
    'pijiu'     => array(
      'url'     => '/images/smilies/pijiu.gif',
      'title'   => '啤酒'
    ),
    'shouqiang' => array(
      'url'     => '/images/smilies/shouqiang.gif',
      'title'   => '手枪'
    ),
    'xiaoyanger'=> array(
      'url'     => '/images/smilies/xiaoyanger.gif',
      'title'   => '小样儿'
    ),
    'xigua'     => array(
      'url'     => '/images/smilies/xigua.gif',
      'title'   => '西瓜'
    ),
    'yangtuo'   => array(
      'url'     => '/images/smilies/yangtuo.gif',
      'title'   => '羊驼'
    ),
    'youling'   => array(
      'url'     => '/images/smilies/youling.gif',
      'title'   => '幽灵'
    )
  );
  echo urldecode(json_encode($array));
?>
