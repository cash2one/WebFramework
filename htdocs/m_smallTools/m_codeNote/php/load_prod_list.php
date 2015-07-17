<?php

$dir_name = "../data";
$op_str   = "<a href='' id='edit_prod_index_btn'>编辑</a> <a href='' id='view_note_list_btn'>查看注释列表</a>";

$trs = Array();

foreach (glob("$dir_name/*") as $prod_dir) {
    $index_file = $prod_dir . "/prod.index";
    if (! file_exists($index_file)) continue;

    $product = basename($prod_dir);
    $id = md5($product);

    $file_content = file_get_contents($index_file);
    list($time, $prod, $author, $svn, $comment) = explode("", $file_content);
    $svn_addr = "<a target=_blank href='$svn'>$svn</a>";
    $tr = "<tr id='$id'><td>$time</td><td>$prod</td><td>$author</td><td>$svn_addr</td><td>$comment</td><td>$op_str</td></tr>";
    array_push($trs, Array($time, $tr));
}

sort($trs);

foreach (array_reverse($trs) as $tr) {
    echo $tr[1], "\n";
}
