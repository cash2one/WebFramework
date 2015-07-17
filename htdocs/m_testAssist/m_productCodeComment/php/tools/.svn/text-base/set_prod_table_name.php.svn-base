<?php

include(dirname(__FILE__) . "/../dbLib.php");

$codePathInfoArr = dbUtil(LoadCodePathInfoTable);
$nameMapArr      = dbUtil(LoadProdTableNameMapInfoTable);
?>

<table border='1'>
<tr><th>服务名称</th><th>表名</th></tr>

<?php
$tempArr = array();
foreach ($codePathInfoArr as $codeSubArr) {
    $prodName = $codeSubArr["prodName"];
    if (array_key_exists($prodName, $nameMapArr)) {
        array_push($tempArr, "<tr><td>$prodName</td><td>" . $nameMapArr[$prodName] . "</td></tr>");

    } else {
        array_push($tempArr, "<tr><td>$prodName</td><td><input type=text name='$prodName' /></td></tr>");
    }
}

echo implode("\n", array_unique($tempArr));
?>

</table>
