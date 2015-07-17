<?php
//获取一个帖子的历史版本记录

require_once("Post.class.php");

$postid = $_GET["postid"];

$post = new Post();
$result = $post->getHistoryVersions($postid);
$post->closeDB();
echo json_encode($result);

?>
