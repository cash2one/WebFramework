<?php
//获取分享帖子的内容，输入postid

require_once("Post.class.php");

$postid = $_GET["postid"];

$post = new Post();
$result = $post->getPostInfo($postid);
$post->closeDB();
echo json_encode($result);

?>
