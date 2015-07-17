<?php

$tname     = $_POST["tname"];
$book_name = $_POST["name"];
$book_tags = $_POST["tags"];
$douban    = $_POST["douban"];

include("./sqlite_lib.php");
$ret = update_book_info($tname, $book_name, $book_tags, $douban);
echo $ret;
