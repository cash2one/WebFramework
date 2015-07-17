<?php
require_once("api.php");

$op = $_GET["op"];
$listName = $_GET["whitelistName"];
$content = $_GET["content"];

if($op == "new_name"){
    $ret = addListName($listName);
    echo json_encode($ret);
}elseif($op == "view_content"){
    $ret = getListContentsByName($listName);
    echo json_encode($ret);
}elseif($op == "add_content"){
    $ret = addListContent($listName,$content);
    echo json_encode($ret);
}elseif($op == "delete_content"){
    $ret = deleteListContent($listName,$content);
    echo json_encode($ret);
}
?>
