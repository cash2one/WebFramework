<?php

$_server_name = null;
$_server_port = "3306";
$_username = null;
$_password = null;

function setMysqlAuth($server_name, $server_port, $username, $password) {
    global $_server_name, $_server_port, $_username, $_password;
    $_server_name = $server_name;
    $_server_port = $server_port;
    $_username = $username;
    $_password = $password;
}

function getConn() {
    global $_server_name, $_server_port, $_username, $_password;
    return mysql_connect($_server_name . ":" . $_server_port, $_username, $_password);
}

function getDBList() {
    $dbs = array();
    $sql_str = "show databases";
    $conn = getConn();
    $result  = mysql_query($sql_str, $conn);
    while($row = mysql_fetch_row($result)) {
        array_push($dbs, $row[0]);
    }
    mysql_free_result($result);
    return $dbs;
}

function getTableList($db_name) {
    $tableNames = array();
    $sql_str = "show tables";
    $conn = getConn();
    mysql_select_db($db_name, $conn);
    $result  = mysql_query($sql_str, $conn);

    while($row = mysql_fetch_row($result)) {
        array_push($tableNames, $row[0]);
    }
    mysql_free_result($result);
    sort($tableNames);
    return $tableNames;
}

function getFieldNameList($db_name, $table_name) {
    $fieldNames = array();
    $sql_str = "desc `$table_name`";
    $conn = getConn();
    mysql_select_db($db_name, $conn);
    $result  = mysql_query($sql_str, $conn);

    while($row = mysql_fetch_row($result)) {
        array_push($fieldNames, array($row[0], $row[1]));
    }
    mysql_free_result($result);

    return $fieldNames;
}

/*
setMysqlAuth("tb081", "3306", "lili", "lili");
print_r(getDBList());
print_r(getTableList("eadb3"));
print_r(getFieldNameList("eadb3", "SPONSOR_MONTHLY_SETTLEMENT"));
*/

?>
