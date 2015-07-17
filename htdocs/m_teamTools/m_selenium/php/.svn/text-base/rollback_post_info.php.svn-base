<?php
//将一个帖子回滚到某个历史版本
require_once("Post.class.php");

$postid = $_GET["postid"];
$historyid = $_GET["historyid"];

$post = new Post();
$post->rollback($postid,$historyid);
echo "1";

$post->closeDB();

?>
