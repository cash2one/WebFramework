<?php

$user = $_POST["user"];
$title = $_POST["title"];
$desc = $_POST["desc"];
$cateName = $_POST["cateName"];
$corePoint = $_POST["corePoint"];

include("dbUtil.php");
dbUtil(SaveInputContent, array($user, $title, $desc, $cateName, $corePoint));
echo json_encode($retArray);
