<?php

date_default_timezone_set("PRC");

$time_str = $_GET["param"];
$time_str = str_replace(" ", ":", $time_str);
$time_str = str_replace("-", ":", $time_str);
$fields = explode(":", $time_str);

echo mktime((int)$fields[3], (int)$fields[4], (int)$fields[5], $fields[1], $fields[2], $fields[0]);
