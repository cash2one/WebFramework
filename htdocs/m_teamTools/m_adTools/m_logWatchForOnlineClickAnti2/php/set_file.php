<?php
date_default_timezone_set("PRC");

$query_click_log = $_GET["click"];
$query_anti_log  = $_GET["anti"];
$filter_str      = $_GET["filter_str"];
$username        = $_GET["username"];

function save_log($username, $filter_str) {
    $file = "../data/log.txt";
    $time_str = strftime("%Y-%m-%d %H:%M:%S", time());    
    $line = "<tr><td>$time_str</td><td>$username</td><td>$filter_str</td></tr>\n";
    file_put_contents($file, $line, FILE_APPEND);
}
save_log($username, $filter_str);

$filename = time();
$ret_array = Array(
    0,
    $filename,
);

$set_file = "../data/set_dir/$filename";
$content = "$query_click_log$query_anti_log$filter_str";
$ret_len = file_put_contents($set_file, $content);
if ($ret_len != strlen($content)) {
    $ret_array = Array(1, "写文件失败");
}

echo json_encode($ret_array);
