<?php

date_default_timezone_set("PRC");

$product = stripslashes($_GET["product"]);
$product = trim($product);

$id = stripslashes($_GET["id"]);

$ret_obj = Array(
    "ret" => 0,
    "msg" => "删除成功",
);

$title_file = "../data/$product/$file_main" . str_replace("_title", ".title", $id);
$desc_file = "../data/$product/$file_main" . str_replace("_title", ".content", $id);
$time_str = strftime("%Y-%m-%d %H:%M:%S", time());

if (!unlink($title_file) || !unlink($desc_file)) {
    $ret_obj["ret"] = 1;
    $ret_obj["msg"] = "错误:文件不存在!";
}

echo json_encode($ret_obj);
