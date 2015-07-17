<?php

define("URLDB", "../data/url_db");

$url   = $_GET["url"];
$title = htmlspecialchars($_GET["title"], ENT_QUOTES);
$desc  = htmlspecialchars($_GET["desc"], ENT_QUOTES);
$class_str = $_GET["class_str"];

$class_str = str_replace("，", ",", $class_str);
$class_str = str_replace(" ", "", $class_str);
$class_str = trim($class_str, ",");

/*
$url   = "http://www.baidu.com1";
$title = "baidu";
$desc  = "最大的中文搜索引擎";
$class_str = "搜索引擎";
*/

if ($url == "") {
    echo "URL不能为空";
    exit(1);
}

if ($db = sqlite_open(URLDB, 0666, $sqliteerror)) {
    $error_msg = "";
    if (! @sqlite_query($db, "INSERT INTO url_info VALUES ('" . md5($url) . "', '$url', '$title', '$desc', '$class_str')", $result_type, $err_msg)) {
        echo "ERROR: 插入失败: $err_msg!";
    } else {
        echo "0";
    }
} else {
    echo $sqliteerror;
}
