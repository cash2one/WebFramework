<?php

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set("PRC");

$device = $_POST["device"];
$user = $_POST["user"];
$time = time();
file_put_contents("./data/log.txt", "$time$user$device\n", FILE_APPEND);
?>
