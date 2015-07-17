<?php

define("URLDB", "../data/url_db");

if ($db = sqlite_open(URLDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select class from url_info', SQLITE_ASSOC);
    $ret_array = sqlite_fetch_all($result); 

    $cate_list = Array();
    foreach ($ret_array as $row_array) {
        $cate_list = array_merge($cate_list, explode(",", $row_array["class"]));
    }
    echo "<option value='all'>all</option>\n";
    sort($cate_list);
    foreach (array_unique($cate_list) as $cate) {
        $count_arr = array_count_values($cate_list);
        $cnt = $count_arr[$cate];
        echo "<option value='$cate'>$cate($cnt)</option>\n";
    }
} else {
    echo "<option>all</option>";
}
