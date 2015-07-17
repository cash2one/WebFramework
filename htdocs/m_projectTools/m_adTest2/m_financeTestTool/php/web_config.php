<?php

$conf_dir = "../conf";
$conf_file_list = Array();
$handle = opendir($conf_dir);
while (false !== ($file = readdir($handle))) {
    if ($file[0] == ".") continue;
    array_push($conf_file_list, "<input name='conf_rd' type=radio for=" . $file . "> " . $file);
}
closedir($handle);

sort($conf_file_list);
echo implode("<br>", $conf_file_list);
