<?php

$server_name = $_POST["server_name"];
$server_port = $_POST["server_port"];
$username    = $_POST["username"];
$password    = $_POST["password"];
$db_name     = $_POST["db_name"];
$table_name  = $_POST["table_name"];
$edit_mode   = $_POST["edit_mode"];

include("./mysqlUtil.php");
include("./mongoUtil.php");
setMysqlAuth($server_name, $server_port, $username, $password);

$tableCommentMap = getTableComment($server_name, $server_port, $db_name, $table_name);
$fieldNames = getFieldNameList($db_name, $table_name);
echo "<caption>$table_name</caption>", "\n";
echo "<tr class='head'><th>字段名</th><th>类型</th><th>中文名</th><th>备注</th></tr>", "\n";
foreach ($fieldNames as $fieldNameList) {
    list($fieldName, $fieldType) = $fieldNameList;
    $chineseName = "";
    $commentStr = "";
    if (array_key_exists($fieldName, $tableCommentMap)) {
        list($chineseName, $commentStr) = $tableCommentMap[$fieldName];
    }
    if ($edit_mode == 1) {
        echo "<tr class='item' name='$fieldName'><td>" . $fieldName . "</td><td>" . $fieldType . "</td><td><textarea class='ch' rows=5 cols=10>$chineseName</textarea><td><textarea class='comment' rows=5 cols=50>$commentStr</textarea></td></tr>", "\n";

    } else {
        $commentStr = str_replace("\n", "<br>", $commentStr);
        echo "<tr class='item' name='$fieldName'><td>" . $fieldName . "</td><td>" . $fieldType . "</td><td>$chineseName</td><td><pre>$commentStr</pre></td></tr>", "\n";
    }
}

?>
