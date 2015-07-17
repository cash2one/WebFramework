<?php

$conf_file = $_POST["conf_file"];
$op_str = "<a name='update_state' href=''>更新数据库</a> <a name='del_state' href=''>删除备份</a>";

$conf_filename = basename($conf_file);
list($main_name, $suffix_name) = explode(".", $conf_filename);

$results_dir = "../results/$main_name";
if (! file_exists($results_dir)) {
    mkdir($results_dir);
}

$index_file  = $results_dir . ".index";
if (! file_exists($index_file)) {
    touch($index_file);
}

$name_map = Array();
$list = file($index_file);
foreach ($list as $line) {
    $line = trim($line);
    list($md5, $state_name) = explode("", $line, 2);
    $name_map[$md5] = $state_name;
}

$file_list = Array();
$handle = opendir($results_dir);
while (false !== ($file = readdir($handle))) {
    if ($file[0] == ".") continue;
    $state_name = $name_map[$file];
    array_push($file_list, $state_name);
}

sort($file_list);
foreach ($file_list as $state_name) {
    echo "<tr><td>$state_name</td><td>$op_str</td></tr>";
}
