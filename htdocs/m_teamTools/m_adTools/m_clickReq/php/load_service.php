<?php

$date = $_GET["date"];
$dir = "../click_data/$date";
chdir($dir);
$serv_list = glob("*");
sort($serv_list);
echo "<option>" . implode("</option><option>", array_reverse($serv_list)) . "</option>";
