<?php
header('Content-type:text/json');
date_default_timezone_set("PRC");

chmod(__FILE__, 0777);

// 在根目录下增加tmp目录的路径
$currentPath = "comments/" . $_POST["postID"] . "/";
$addFilePath = dirname(dirname(dirname(__FILE__))) . "/uploads/" . $currentPath;

if(!is_dir($addFilePath)) mkdir($addFilePath, 0777, true);

if ($_FILES['file'] !== null) {
  // 上传文件大小
  $fileSize = filesize($_FILES["file"]["tmp_name"]);

  // 截取后缀名
  $fileEx = strtolower(substr(strrchr($_FILES["file"]["name"], "."), 1));

  // 生成随机文件名
  $fileName = date("YmdHis") . substr(rand(), 0, 6) . "." . $fileEx;

  // 移动文件到指定目录
  move_uploaded_file($_FILES["file"]["tmp_name"], $addFilePath . $fileName);

  // 判断发表文章的时候是否提交了本次的图片，未提交从服务器删除本图片
  if ($_POST["mark"] === "upload") {
    $result = array(
      "name"  => urlencode($fileName),
      "size"  => ceil($fileSize / 1024) . "Kb",
      "type"  => urlencode($_FILES["file"]["type"]),
      "path"  => $_POST["url"] . '/uploads/' . $currentPath . urlencode($fileName),
      "code"  => $_POST["mark"]
    );
  } else {
    $result = array(
      "code" => unlink(dirname(dirname(dirname(__FILE__))) . "/uploads/" . $currentPath . $_POST['fileName'])
    );
  }
} else {
  // 上传文件为base64格式
  if ($_POST["mark"] === "upload") {
    // 截取图片类型
    preg_match('/data:\s*image\/(?P<type>(\w+));base64,(?P<base64>\S+)/', $_POST['file'], $result);

    // 生成文件名字
    $fileName = date("YmdHis") . substr(rand(), 0, 6) . '.' . $result['type'];

    // 生成文件
    file_put_contents($addFilePath . $fileName, base64_decode($result['base64']));

    $result = array(
      "name"  => urlencode($fileName),
      "type"  => urlencode("image/" . $result['type']),
      "path"  => $_POST["url"] . '/uploads/' . $currentPath . urlencode($fileName),
      "code"  => $_POST["mark"]
    );
  } else {
     $result = array(
       "code" => unlink(dirname(dirname(dirname(__FILE__))) . "/uploads/" . $currentPath . $_POST['fileName'])
     );
   }
}

// 输出结果
echo urldecode(json_encode($result));
?>
