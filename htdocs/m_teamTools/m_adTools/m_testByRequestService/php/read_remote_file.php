<?php

date_default_timezone_set("Asia/Shanghai");

// get from the font-end
$host = stripslashes($_GET["host"]);
$path = stripslashes($_GET["path"]);

/*
$host = "nb093";
$path = "/tmp/abc";
*/

// ========================== Variables Definitions ==============================
// Constant Variables
define("LINE_CNT", 1000);

// used to return to the front as json format
$ret_array = Array(
    "status" => 0,
    "message" => "",
    "lines" => Array(),
);

// ========================== Main Logic ==============================
$count = LINE_CNT;
$cmd   = "ssh $host 'head -$count $path' 2>&1";

exec($cmd, $lines, $ret);
if ($ret != 0) {
    $ret_array["status"] = $ret;
    $ret_array["message"] = implode(",", $lines);

} else {
    $ret_array["lines"] = $lines;
}

echo json_encode($ret_array);
