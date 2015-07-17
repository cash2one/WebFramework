<?php

$platformList = Array("EADM", "EADC", "EADU", "OFFLINE", "EADS", "EADD", "DSP");

$platformFilterList = Array(
    "DSP" => Array(),
    "EADC" => Array(),
    "EADM" => Array(),
    "EADU" => Array(),
    "EADS" => Array(),
    "EADD" => Array(),
    "OFFLINE" => Array(),
);

$platformDict = Array(
    "DSP" => "智选",
    "EADC" => "频道",
    "EADM" => "邮箱",
    "EADU" => "联盟",
    "EADS" => "搜索",
    "EADD" => "词典",
    "OFFLINE" => "线下直销",
);


$dataArray = Array(
    "DSP" => Array(),
    "EADC" => Array(),
    "EADM" => Array(),
    "EADU" => Array(),
    "EADS" => Array(),
    "EADD" => Array(),
    "OFFLINE" => Array(),
);

$dirs = glob("../data/2013????");
sort($dirs);
foreach (array_splice($dirs, -30) as $dir) {
    $dateStr = basename($dir);

    $files = glob("$dir/*");
    foreach($files as $file) {
        $platform = basename($file);                
        $dataArray[$platform][$dateStr] = Array();

        $lines = file($file);
        foreach($lines as $line) {
            $line = trim($line);
            list($filterName, $cnt) = explode(":", $line, 2); 
            $dataArray[$platform][$dateStr][$filterName] = $cnt;

            if ($filterName == "valid" || $filterName == "unValid") 
                continue;

            if (! in_array($filterName, $platformFilterList[$platform])) {
                array_push($platformFilterList[$platform], $filterName);
            }
        }
    }
}

$lines = Array();

$linkStr = "";
foreach($platformList as $platform) {
    $linkStr .= "<a href='$platform'>" . $platformDict[$platform] . "</a> ";
    array_push($platformFilterList[$platform], "被过滤的总点击数");
    array_push($platformFilterList[$platform], "有效的点击");
}
array_push($lines, $linkStr);
array_push($lines, "<br>");
array_push($lines, "<br>");

foreach ($platformList as $platform) {
    array_push($lines, "<table border='1' id='$platform'>");
    array_push($lines, "<tr><th colspan='" . (count($platformFilterList[$platform]) + 1) . "'>$platform</th></tr>");
    array_push($lines, "<tr><th>日期/过滤规则</th><th>" . implode("</th><th>", $platformFilterList[$platform]) . "</th></tr>");

    foreach($dataArray[$platform] as $dateStr => $filterArray) {
        $temp_array = Array();
        foreach ($platformFilterList[$platform] as $filterName) {
            if ($filterName == "有效的点击") 
                $filterName = "valid";
            else if ($filterName == "被过滤的总点击数") 
                $filterName = "unValid";

            if (! array_key_exists($filterName, $filterArray)) {
                $filterArray[$filterName] = 0;
            }

            array_push($temp_array, $filterArray[$filterName]);
        }

        $dateStr = substr($dateStr, 0, 4) . "年" . substr($dateStr, 4, 2) . "月" . substr($dateStr, 6, 2) . "日";
        array_push($lines, "<tr><td>$dateStr</td><td>" . implode("</td><td>", $temp_array) . "</td></tr>");
    }
    
    array_push($lines, "</table>");
}

echo implode("\n", $lines);
