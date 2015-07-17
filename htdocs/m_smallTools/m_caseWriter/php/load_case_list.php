<?php

$id = stripslashes($_POST["id"]);
$file = "../case_list_dir/$id.content";
$ret_obj = Array();

$op_str = "<a href='' id='case_add_btn'>添加</a> <a href='' id='case_edit_btn'>编辑</a> <a href='' id='case_del_btn'>删除</a> <a href='' id='case_copy_btn'>复制</a> <a href='' id='move_up'>上移</a> <a href='' id='move_down'>下移</a>";

if (file_exists($file)) {
    $lines = file($file);  
    foreach ($lines as $line) {
        list($cate, $title, $comment, $status) = explode("", $line);
        $comment = str_replace("", "<br>", $comment);
        echo "<tr><td>$cate</td><td>$title</td><td>$comment</td><td>$status</td><td>$op_str</td></tr>\n";
    }
}
