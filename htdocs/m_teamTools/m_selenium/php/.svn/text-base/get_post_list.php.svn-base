<?php
//获取分享帖子的列表，输入deleted参数值为true时，返回回收站中的帖子，值为false时，返回未删除的帖子

require_once("Post.class.php");

$deleted = $_GET["deleted"];

$post = new Post();
if($deleted == "true"){
    $result = $post->getTrash();
}else{
    $result = $post->getAll();
}
$post->closeDB();
echo json_encode($result);

?>
