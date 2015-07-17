<?php

$user = $_GET["user"];
$info = $_GET["info"];
$count_in_row = (int)$_GET["count_in_row"];
$period = $_GET["period"];
$start_ts = $_GET["start_ts"];
$end_ts = $_GET["end_ts"];

$tds = Array();
$info_list = explode(";", $info);
foreach ($info_list as $info) {
    $file = "../vaquero_data/$user/$info";
    $lines = file($file);

    foreach ($lines as $line) {
        list($prod, $type, $cub, $url) = explode(":", $line, 4);
        if ($period != "manual") {
            array_push($tds, "<td><img src='" . trim($url) . "&period=$period" . "'/><div class='center'><input type=checkbox  checked />$prod-$type-$cub</div></td>");
        } else {
            array_push($tds, "<td><img src='" . trim($url) . "&period=$period&start=$start_ts&end=$end_ts" . "'/><div class='center'><input type=checkbox  checked />$prod-$type-$cub</div></td>");
        }
    }
}

for ($i = 0; $i < count($tds); $i++) {
    if ($i % $count_in_row == 0) {
        if ($i != 0) { 
            echo "</tr>";
        }
        echo "<tr>";
    }
    echo $tds[$i];
}
echo "</tr>";
