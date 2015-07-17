<?php

$temp_list = Array();

$lines = file("url.api");
$lines = array_merge_recursive($lines, file("url.api.custom"));
sort($lines);
foreach ($lines as $line) {
    $line = trim($line);
    list($name, $url) = preg_split("/\s*-\s*/", $line, 2);

    if (substr($url, 0, 5) == "https") {
        array_push($temp_list, "<option value='$url'>$name(https)</option>");
    } else {
        array_push($temp_list, "<option value='$url'>$name</option>");
    }
}

echo implode("\n", $temp_list);
