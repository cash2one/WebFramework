<?php

$dir_name = "../case_list_dir";
$op_str   = "<a href='' id='edit_case_index_btn'>编辑</a> <a href='' id='view_case_list_btn'>查看用例</a> ";

$trs = Array();

foreach (glob("$dir_name/*.title") as $title_file) {
    $id = str_replace(".title", "", basename($title_file));
    $file_content = file_get_contents($title_file);
    list($time, $title, $service_name, $user, $ticket_addr, $comment) = explode("", $file_content);
    $ticket_addr = "<a target=_blank href='$ticket_addr'>$ticket_addr</a>";
    array_push($trs, Array($time, "<tr id='$id'><td>$time</td><td>$title</td><td>$service_name</td><td>$user</td><td>$ticket_addr</td><td>$comment</td><td>$op_str</td></tr>"));
}

sort($trs);

foreach (array_reverse($trs) as $tr) {
    echo $tr[1], "\n";
}
