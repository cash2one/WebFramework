<?php

$serviceName = $_POST["serviceName"];
$verName     = $_POST["verName"];

$retArray = array();
if ($serviceName == "" && $verName == "") {
    $retArray = array(1, "错误：输入不能为空!");

} else {
    include("dbUtil.php");
    dbUtil(AddNewVersion, array($serviceName, $verName));
}

echo json_encode($retArray);
