<?php

define("CODEDB", "../data/code_db");

$title = $_POST["title"];
$content = htmlspecialchars($_POST["content"], ENT_QUOTES);
$language = strtolower($_POST["language"]);

/*
$title = "php遍历目录";
$content = file_get_contents("a.txt");
$language = "php";
*/

if ($title == "") {
    echo "title不能为空";
    exit(1);
}

if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    $error_msg = "";
    if (! @sqlite_query($db, "INSERT INTO code_info VALUES (null,'" . $title . "', '$content', '$language')", $ret, $msg)) {
        echo "ERROR: 插入失败!\n";
        echo $msg;
    } else {
        echo "0";
    }
} else {
    echo $sqliteerror;
}
