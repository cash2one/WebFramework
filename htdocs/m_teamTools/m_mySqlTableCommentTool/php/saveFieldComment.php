<?php

$server_name = $_POST["server_name"];
$server_port = $_POST["server_port"];
$db_name     = $_POST["db_name"];
$table_name  = $_POST["table_name"];
$field_list_str = $_POST["field_str"];

include("./mongoUtil.php");

$fieldList = explode("", $field_list_str);
$tempList = array();
foreach ($fieldList as $fieldStr) {
    list($fieldName, $chineseName, $commentStr) = explode("", $fieldStr);
    array_push($tempList, array(
                            "fieldName" => $fieldName,
                            "chineseName" => $chineseName,
                            "commentStr" => $commentStr,
                        )
                );
}

saveComment($server_name, $server_port, $db_name, $table_name, $tempList);
echo "保存成功!";
