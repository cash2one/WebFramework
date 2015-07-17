<?php

$type    = $_POST["type"];
$id      = $_POST["id"];
$title   = $_POST["title"];
$summary = $_POST["summary"];
$wiki    = $_POST["wiki"];
$home    = $_POST["home"];
$svn     = $_POST["svn"];
$status  = $_POST["status"];
$creator = $_POST["creator"];
$members = $_POST["members"];

include("./sqlite_lib2.php");

$param_arr = Array(
    $id,
    $title,
    $summary,
    $wiki,
    $home,
    $svn,
    $status,
    $creator,
    $members
);

if ($type == "new") {
    add_edit_project($param_arr, 0);

} elseif ($type == "edit") {
    add_edit_project($param_arr, 1);

} else {
    echo "错误，无效的类型!";
}
