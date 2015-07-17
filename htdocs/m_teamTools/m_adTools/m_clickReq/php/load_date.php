<?php

$dir = "../click_data";
chdir($dir);
$date_list = glob("*-*-*");
sort($date_list);
echo "<option>" . implode("</option><option>", array_reverse($date_list)) . "</option>";
