<?php

$product = stripslashes($_POST["product"]);
$prod_dir_name = "../data/$product";

$op_str = "<a href='' id='note_detail'>查看细节</a> <a href='' id='note_edit_btn'>编辑</a> <a href='' id='note_del_btn'>删除</a>";

$lines = Array();

$files = glob($prod_dir_name . "/*.title");
foreach ($files as $file) {
    $file_content = file_get_contents($file);
    $file_name = basename($file); 
    $id = str_replace(".title", "_title", $file_name);

    list($time_str, $title, $sponsor) = explode("", $file_content);
    array_push($lines, "<tr id='$id'><td class='update_time'>$time_str</td><td>$title</td><td>$sponsor</td><td class='op_str'>$op_str</td></tr>");
}

echo implode("\n", $lines);
