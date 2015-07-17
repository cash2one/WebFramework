<?php

define("CODEDB", "../data/code_db");

if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    $error_msg = "";
    if (! @sqlite_query($db, "delete from code_info where id" . 10)) {
        echo "ERROR: 插入失败!\n";
        echo $msg;
    } else {
        echo "0";
    }
} else {
    echo $sqliteerror;
}
