<?php

$prodName    = $_POST["prodName"];
$versionName = $_POST["version"];
$timestamp   = time();

include("interface.php");
include("dbUtil.php");
$status = getStatus();
if ($status == "stopped") {
    dbUtil(SaveRunInfo, array($prodName, $versionName, $timestamp));
}

$ret = runTool($prodName, $versionName, $timestamp);
