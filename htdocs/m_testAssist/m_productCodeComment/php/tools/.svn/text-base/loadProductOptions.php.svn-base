<?php

include(dirname(__FILE__) . "/../dbLib.php");
$temp_arr = array();

$prodInfoArr = dbUtil(LoadCodePathInfoTable);
foreach ($prodInfoArr as $subArr) {
    array_push($temp_arr, "<option>" . $subArr["prodName"] . "</option>");
}

echo implode("\n", array_unique($temp_arr));
