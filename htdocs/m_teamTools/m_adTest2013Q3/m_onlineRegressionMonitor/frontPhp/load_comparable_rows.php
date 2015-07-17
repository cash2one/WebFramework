<?php

include("interface.php");
list($prod1, $ver1, $ts1) = get_detail($_POST["path1"]);
list($prod2, $ver2, $ts2) = get_detail($_POST["path2"]);

$rows1 = getAllSumData($prod1, $ver1, $ts1);
$rows2 = getAllSumData($prod2, $ver2, $ts2);

$retArray = array(
    array(),
    array(),
);

function get_detail($str) {
    return explode(":", $str);
}

$index = 0;
array_push($retArray[0], "<table border='1'>");
foreach ($rows1 as $row) {
    if ($index == 0) {
        array_push($retArray[0], "<tr><th colspan='" . (count($row) + 1) . "'>$prod1 - $ver1 - $ts1(". date("Y-m-d H:i:s", $ts1) . ")</th></tr>");
        array_push($retArray[0], "<tr><th>行号</th><th>" . implode("</th><th>", $row) . "</th></tr>");
    } else {
        array_push($retArray[0], "<tr><th>$index</th><td>" . implode("</td><td>", $row) . "</td></tr>");
    }
    $index += 1;
}
array_push($retArray[0], "</table>");

$index = 0;
array_push($retArray[1], "<table border='1'>");
foreach ($rows2 as $row) {
    if ($index == 0) {
        array_push($retArray[1], "<tr><th colspan='" . (count($row) + 1) . "'>$prod2 - $ver2 - $ts2(". date("Y-m-d H:i:s", $ts2) . ")</th></tr>");
        array_push($retArray[1], "<tr><th>行号</th><th>" . implode("</th><th>", $row) . "</th></tr>");
    } else {
        array_push($retArray[1], "<tr><th>$index</th><td>" . implode("</td><td>", $row) . "</td></tr>");
    }
    $index += 1;
}
array_push($retArray[1], "</table>");

echo json_encode( array(implode("\n", $retArray[0]), implode("\n", $retArray[1])) );
