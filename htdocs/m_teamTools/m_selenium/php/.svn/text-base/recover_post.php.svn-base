<?php
//从回收站恢复一个帖子
require_once("Post.class.php");

$postid = $_GET["postid"];

$post = new Post();
$post->recover($postid);
$post->closeDB();
echo "1";

?>
