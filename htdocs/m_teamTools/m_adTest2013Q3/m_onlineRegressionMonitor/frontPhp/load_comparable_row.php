<?php

include("interface.php");
list($prod1, $ver1, $ts1) = get_detail($_POST["path1"]);

$rows1 = getAllSumData($prod1, $ver1, $ts1);

$retArray = array();

function get_detail($str) {
    return explode(":", $str);
}

$index = 0;
array_push($retArray, "<table border='1'>");
foreach ($rows1 as $row) {
    if ($index == 0) {
        array_push($retArray, "<tr><th colspan='" . (count($row) + 1) . "'>$prod1 - $ver1 - $ts1(". date("Y-m-d H:i:s", $ts1) . ")</th></tr>");
        array_push($retArray, "<tr><th>行号</th><th>" . implode("</th><th>", $row) . "</th></tr>");
    } else {
        array_push($retArray, "<tr><th>$index</th><td>" . implode("</td><td>", $row) . "</td></tr>");
    }
    $index += 1;
}
array_push($retArray, "</table>");

echo implode("\n", $retArray);
