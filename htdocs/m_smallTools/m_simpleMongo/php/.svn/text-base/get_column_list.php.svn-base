<?php

$host = $_GET["host"];
$port = $_GET["port"];
$user = $_GET["user"];
$password = $_GET["password"];
$dbname = $_GET["dbname"];
$table = $_GET["table"];


$cmd = "./python/get_columns.py '$host' '$port' '$user' '$password' '$dbname' '$table'";
exec($cmd, $output, $ret);
if ($ret != 0) {
    echo Array();    
} else {
    echo $output[0];
}
