<?php
//删除一个帖子
require_once("Post.class.php");

$postid = $_GET["postid"];

$post = new Post();
$post->delete($postid);
$post->closeDB();
echo "1";

?>
