<?php

define("URLDB", "../data/url_db");

if ($db = sqlite_open(URLDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select class from url_info', SQLITE_ASSOC);
    $ret_array = sqlite_fetch_all($result); 

    $cate_list = Array();
    foreach ($ret_array as $row_array) {
        $cate_list = array_merge($cate_list, explode(",", $row_array["class"]));
    }
    foreach (array_unique($cate_list) as $cate) {
        echo "<input type=checkbox for='$cate''>$cate ";
    }
}
