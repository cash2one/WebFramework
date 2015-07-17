<?php

$html_data_dir = "../html_data";
$status_list_dir = "./status-list";

function get_saved_status_names() {
    global $html_data_dir, $status_list_dir;
    $ret_status_names = Array();

    $files = glob("$html_data_dir/case_*.*.html");
    foreach ($files as $file) {
        preg_match("/case_\d+\.(.*)\.html/", $file, $matches);
        $filename = $matches[1];
        $state_name = file_get_contents("$status_list_dir/$filename");
        array_push($ret_status_names, $state_name);
    }
    return $ret_status_names;
}

function get_status_list() {
    global $status_list_dir;
    $ret_status_names = Array();
    $files = glob("$status_list_dir/*");

    foreach ($files as $file) {
        $state_name = file_get_contents($file);
        array_push($ret_status_names, $state_name);
    }
    return $ret_status_names;
}

function get_html_str($saved_status_names, $all_status_names) {
    // sort by name
    sort($saved_status_names);
    sort($all_status_names);

    $lines1 = Array();
    foreach ($saved_status_names as $name) {
        $line = "<tr><td class='status_name'>$name</td><td><a href='' class='set_status'>设定为数据库状态</a> <a href='' class='del_status'>删除此备份状态</a></td></tr>";
        array_push($lines1, $line);
    }
    $lines1_str = implode("\n", $lines1);

    $lines2 = Array();
    foreach ($all_status_names as $name) {
        if (! in_array($name, $saved_status_names)) {
            $line = "<tr><td class='status_name'>$name</td><td><a href='' class='archive_status'>备份数据库状态</a> <a href='' class='del_name'>删除状态名</a></td></tr>";
            array_push($lines2, $line);
        }
    }
    $lines2_str = implode("\n", $lines2);

    return json_encode(Array(
        "table1" => $lines1_str,
        "table2" => $lines2_str,
    ));
}

function get_json_str() {
    $saved_status_names = get_saved_status_names();
    $all_status_names  = get_status_list();
    return get_html_str($saved_status_names, $all_status_names);
}

echo get_json_str();
