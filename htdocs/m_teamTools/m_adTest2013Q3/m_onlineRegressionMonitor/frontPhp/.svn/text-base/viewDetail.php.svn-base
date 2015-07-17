<html>

<head>
    <style>
        a {text-decoration: none}
    </style>
</head>

<body>
<?php

include("interface.php");

list($prod, $ver, $ts, $time) = get_detail($_GET["info"]);
$sortName = getParam("sortName", "");
$sortType = getParam("sortType", 1);
$rows = getDetailData($prod, $ver, $ts, $time, $sortName, $sortType * -1);

function get_detail($str) {
    return explode(":", $str);
}

function getParam($paramName, $defValue) {
    global $_GET;
    if (array_key_exists($paramName, $_GET)){
        return $_GET[$paramName];
    }
    return $defValue;
}
?>

<table border='1'>
<?php
    $index = 0;
    foreach ($rows as $row) {
        if ($index == 0) {
            echo "<tr><th colspan='" . (count($row) + 1) . "'>$prod - $ver - $ts </th></tr>\n";
            echo "<tr><th>行号</th><th>" . implode("</th><th>", $row) . "</th></tr>\n";

        } else {
            echo "<tr><th>$index</th><td>" . implode("</td><td>", $row) . "</td></tr>\n";
        }
        $index += 1;
    }
?>
</table>
</body>
</html>
