<?php
header("Content-type:text/json");
date_default_timezone_set("PRC");

chmod(__FILE__, 0777);

// 拼接当前图片的路径
$id = $_POST["postID"];
$uploadFilePath = dirname(dirname(dirname(__FILE__)));
$currentPath = "/uploads/comments/$id/";
$fullPath = $uploadFilePath . $currentPath;
$result = array();

if(!is_dir($fullPath)) mkdir($fullPath, 0777, true);

if ($_FILES["file"] === null && $_POST['file'] === null) {
  $result = array(
    "message" => "上传失败，图片为空！",
    "code" => "4001"
  );
} else {
  // 上传文件大小
  $fileSize = ceil(filesize($_FILES["file"]["tmp_name"]) / 1024) . "Kb";

  // 生成后缀名
  preg_match('/data:image\/(?P<type>(\w+));base64,(?P<base64>\S+)/', $_POST['file'], $result);
  $fileEx = $_POST['file']
    ? $result["type"]
    : strtolower(substr(strrchr($_FILES["file"]["name"], "."), 1));

  // 生成随机文件名
  $fileName = $_POST["name"] ? $_POST["name"] ."." . $fileEx : date("YmdHis") . substr(rand(), 0, 6) . "." . $fileEx;

  $fileType = "";

  // 移动文件到指定目录
  if ($_POST["file"]) {
    file_put_contents($fullPath . $fileName, base64_decode($result["base64"]));
    $fileType = urlencode("image/" . $result["type"]);
  } else {
    move_uploaded_file($_FILES["file"]["tmp_name"], $fullPath . $fileName);
    $fileType = urlencode($_FILES["file"]["type"]);
  }

  // 判断发表文章的时候是否提交了本次的图片，未提交从服务器删除本图片
  if ($_POST["mark"] === "upload") {
    $result = array(
      "name"  => urlencode($fileName),
      "size"  => $fileSize,
      "type"  => $fileType,
      "path"  => $_POST["url"] . $currentPath . urlencode($fileName),
      "code"  => $_POST["mark"]
    );
  } else {
    $result = array(
      "code" => unlink(dirname(dirname(dirname(__FILE__))) . $uploadFilePath . $_POST["fileName"])
    );
  }
}
echo urldecode(json_encode($result));
?>
