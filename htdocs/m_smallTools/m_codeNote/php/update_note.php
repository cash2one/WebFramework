<?php

date_default_timezone_set("PRC");

$product = stripslashes($_GET["product"]);
$product = trim($product);

$id = stripslashes($_GET["id"]);

$title   = stripslashes($_GET["title"]);
$content = stripslashes($_GET["content"]);
$sponsor = stripslashes($_GET["sponsor"]);
$comment = stripslashes($_GET["comment"]);

$ret_obj = Array(
    "ret" => 0,
    "msg" => "保存成功",
);

$title_file = "../data/$product/$file_main" . str_replace("_title", ".title", $id);
$desc_file = "../data/$product/$file_main" . str_replace("_title", ".content", $id);
$time_str = strftime("%Y-%m-%d %H:%M:%S", time());

if (!file_exists($title_file) || !file_exists($desc_file)) {
    $ret_obj["ret"] = 1;
    $ret_obj["msg"] = "错误:文件不存在!";

} else {
    $title_line = implode("", Array($time_str, $title, $sponsor));
    file_put_contents($title_file, $title_line);

    file_put_contents($desc_file, $content);
}

echo json_encode($ret_obj);
