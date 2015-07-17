<?php

$plat_name = $_GET["plat_name"];
$type      = $_GET["type"];

$file_path_pattern = "../result_data/$plat_name/*.$type";
$files = glob($file_path_pattern);

$cur_time = time() * 1000;

$retArray = Array();
foreach ($files as $file) {
    $filename = basename($file);
    $key = str_replace(".$type", "", $filename);
    $retArray[$key] = Array(
        "label" => "$key",
        "data" => Array()
    );

    $lines = file($file);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == "") continue;
        list($timestamp, $val) = explode(":", $line);
        $timestamp = $timestamp * 1;

        # only show one week data
        if ($cur_time - $timestamp > 14 * 24 * 60 * 60 * 1000) continue; 

        $val = (float)$val;
        array_push($retArray[$key]["data"], Array($timestamp, $val));
    }
}

$retArray["filter_rate"] = Array(
    "label" => "filter_rate(%)",
    "data" => Array()
);

for ($i = 0; $i < count($retArray["unValid"]["data"]); $i++) {
    $timestamp = $retArray["unValid"]["data"][$i][0];
    $unValid   = (int)$retArray["unValid"]["data"][$i][1];
    $valid     = (int)$retArray["valid"]["data"][$i][1];
    $rate = $unValid + $valid > 0 ? $unValid / ($unValid + $valid) * 100.0 : 0;
    array_push($retArray["filter_rate"]["data"], Array($timestamp, $rate));
}

echo json_encode($retArray);
