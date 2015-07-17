<?php

date_default_timezone_set("PRC");

$param = $_GET["param"];
echo date("Y-m-d H:i:s", $param);
