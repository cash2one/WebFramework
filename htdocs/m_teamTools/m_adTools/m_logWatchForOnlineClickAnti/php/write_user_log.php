<?php

$username = stripslashes($_GET["username"]);
$filter_str = stripslashes($_GET["filter_str"]);

save_log($username, $filter_str);

function save_log($username, $filter_str) {
    $file = "../log/log.txt";
    $time_str = strftime("%Y-%m-%d %H:%M:%S", time());    
    $line = "<tr><td>$time_str</td><td>$username</td><td>$filter_str</td></tr>\n";
    file_put_contents($file, $line, FILE_APPEND);
}
