<?php

header("Content-type:text/html; charset:utf-8");
include(dirname(__FILE__) . "/../php/dbLib.php");

if (! array_key_exists("op_type", $_POST)) {
    echo "Error: 无效的http post参数!";
    echo "  <a href='../index.php'>返回</a>";
    return;
}

$op_type = $_POST["op_type"];
if ($op_type == "set_prod_table_name") {
    set_prod_table_name();
}

echo "  <a href='../index.php'>返回</a>";

function set_prod_table_name() {
    global $_POST;
    $op_type = "set_prod_table_name";
    foreach ($_POST as $prodName => $tableName) {
        if ($tableName == $op_type) continue;
        
        $ret = dbUtil(SaveProdTableNameMapInfo, $prodName, $tableName);
        $retArr = dbUtil(GetStatus);
        if ($ret != true || $retArr["ret"] != 0) {
            echo $retArr["msg"];
            break;
        }
    }

    echo "Info: 映射产品名与表名成功！";
}
