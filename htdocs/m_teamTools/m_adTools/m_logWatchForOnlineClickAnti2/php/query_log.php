<?php

$set_file = "../data/set_dir/" . $_GET["set_file"];
$username = $_GET["username"];
$password = $_GET["password"];
$ret_array = Array(0, Array(), Array());

function do_query(&$output_lines) {
    global $set_file;
    global $username, $password;
    $exp_file = "./query_log_" . basename($set_file) . ".exp";

    $exp_content = file_get_contents("template/query_log.exp.template");
    $exp_content = str_replace('$current_file$', $exp_file, $exp_content);
    $exp_content = str_replace('$set_file$', $set_file, $exp_content);
    $exp_content = str_replace('$username$', $username, $exp_content);
    $exp_content = str_replace('$password$', $password, $exp_content);

    file_put_contents($exp_file, $exp_content);
    exec("expect $exp_file", $output_lines, $ret);
}

$ret_lines = Array();
do_query($ret_lines);

$fields = explode("", array_pop($ret_lines));
if (count($fields) != 3) {
    $ret_array[0] = 10;  //密码错误
}else {
    $ret_array[0] = (int)$fields[0];
    $ret_array[1] = explode("", $fields[1]);
    $ret_array[2] = explode("", $fields[2]);
}

echo json_encode($ret_array);
