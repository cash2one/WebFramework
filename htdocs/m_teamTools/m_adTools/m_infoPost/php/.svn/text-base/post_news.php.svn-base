<?php

date_default_timezone_set("PRC");

$data = stripslashes($_POST["data"]);
$time_str = strftime("%Y%m%d%H%M%S", time());
file_put_contents("../posts/" . $time_str, $data);
