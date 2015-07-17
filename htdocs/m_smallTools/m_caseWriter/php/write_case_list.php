<?php

// refer(for slashes): http://hi.baidu.com/singles_dating/item/1922db23862cab192b0f1c39

$id        = $_POST["id"];
$param_str = $_POST["param_str"];
$file = "../case_list_dir/$id.content";
$input_obj = json_decode($param_str);

$file_content = "";
foreach ($input_obj as $row) {
    $row = str_replace("\n", "", $row);
    $file_content .= implode("", $row) . "\n";
}

file_put_contents($file, $file_content);
