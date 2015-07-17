<?php

date_default_timezone_set("PRC");

$product = stripslashes($_GET["product"]);
$author  = stripslashes($_GET["author"]);
$svn     = stripslashes($_GET["svn"]);
$comment = stripslashes($_GET["comment"]);
$time_str = strftime("%Y-%m-%d %H:%M:%S", time());

$ret_obj = Array(
    "ret" => 0,
    "msg" => "保存成功",
);

$product = trim($product);
$prod_dir_name = "../data/$product";
$index_file    = $prod_dir_name . "/prod.index";

if (file_exists($prod_dir_name)) {
    $ret_obj["ret"] = 1;
    $ret_obj["msg"] = "错误: 保存失败，产品名($prod_dir_name)已存在";
} else {
    $ret = mkdir($prod_dir_name) && touch($index_file);
    if ($ret == false) {
        $ret_obj["ret"] = 1;
        $ret_obj["msg"] = "错误:创建产品文件夹失败 或 创建index文件失败!";

    } else {
        $line = implode("", Array($time_str, $product, $author, $svn, $comment));
        file_put_contents($index_file, $line);
    }
}

echo json_encode($ret_obj);
