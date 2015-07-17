<?php

$date = $_GET["date"];
$service = $_GET["service"];
$file = "../click_data/$date/$service";

$href_links = Array();
$max_cnt = 50;
foreach(file($file) as $url) {
    array_push($href_links, "<a href='$url'>链接</a>");
    $max_cnt --;
    if ($max_cnt == 0) break;
}
$tr = "<tr><td>$service</td><td>" . implode(" ", $href_links) . "</td></tr>";
echo $tr;
