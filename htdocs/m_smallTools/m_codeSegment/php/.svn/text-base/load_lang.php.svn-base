<?php

define("CODEDB", "../data/code_db");

if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select language from code_info', SQLITE_ASSOC);
    $ret_array = sqlite_fetch_all($result); 

    $cate_list = Array();
    foreach ($ret_array as $row_array) {
        $cate_list = array_merge($cate_list, explode(",", $row_array["language"]));
    }
    echo "<option>all</option>\n";
    sort($cate_list);
    foreach (array_unique($cate_list) as $cate) {
        echo "<option>$cate</option>\n";
    }
} else {
    echo "<option>all</option>";
}
