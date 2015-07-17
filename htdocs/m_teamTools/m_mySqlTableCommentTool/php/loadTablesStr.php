<?php

$server_name = $_POST["server_name"];
$server_port = $_POST["server_port"];
$username    = $_POST["username"];
$password    = $_POST["password"];
$db_name     = $_POST["db_name"];

include("mysqlUtil.php");
include("mongoUtil.php");
setMysqlAuth($server_name, $server_port, $username, $password);
$tables = getTableList($db_name);
$commentedTables = get_comment_table($server_name, $server_port, $db_name);
foreach ($tables as $table) {
    if (in_array($table, $commentedTables)) {
        echo "<tr><td><a href='' class='table commented' name='$table'>$table</a></td></tr>";

    } else {
        echo "<tr><td><a href='' class='table' name='$table'>$table</a></td></tr>";
    }
}

?>
