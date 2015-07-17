<?php

include(dirname(__FILE__) . "/../dbLib.php");
$temp_arr = array();
$prodName = $_POST["prodName"];

$prodInfoArr = dbUtil(LoadCodePathInfoTable);
foreach ($prodInfoArr as $subArr) {
    if ($prodName != $subArr["prodName"]) continue;
    array_push($temp_arr, "<option>" . $subArr["creator"] . "</option>");
}

echo implode("\n", array_unique($temp_arr));
