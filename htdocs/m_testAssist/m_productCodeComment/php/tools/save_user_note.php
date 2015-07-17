<?php

include(dirname(__FILE__) . "/../dbLib.php");

function getValue($keyName) {
    global $_POST;
    if (array_key_exists($keyName, $_POST))
        return $_POST[$keyName];
    exit("1缺少参数$keyName !");
}

$commentor = getValue("userName");
$prodName  = getValue("prodName");
$version   = getValue("version");
$filePath  = getValue("filePath");
$type      = getValue("type");
$detail_type = getValue("detail_type");
$content     = getValue("content");
$selected_id = getValue("selected_id");

dbUtil(SaveCodeCommentInfo, $prodName, $version, "live", $filePath, $commentor, $type, $content, $detail_type, $selected_id);
$retArr = dbUtil(GetStatus);
printf("%s%s", $retArr["ret"], $retArr["msg"]);
