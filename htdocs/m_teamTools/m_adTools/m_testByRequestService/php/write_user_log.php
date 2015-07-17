<?php
date_default_timezone_set("Asia/Shanghai");
$username = stripslashes($_POST["username"]);
$host_info = stripslashes($_POST["machine_info"]);
$content_str = stripslashes($_POST["content"]);

/*
$username = "111";
$host_info = "dfasf";
$content_str = "fdafa";
*/

save_log($username, $host_info, $content_str);

function save_log($username, $host_info, $content_str) {
    $file = "../log/log.txt";
    $time_str = strftime("%Y-%m-%d %H:%M:%S", time());    
    $line = "<tr><td>$time_str</td><td>$username</td><td>$host_info</td><td>$content_str</td></tr>\n";
    file_put_contents($file, $line, FILE_APPEND);
}
