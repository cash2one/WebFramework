<?php

date_default_timezone_set("PRC");

include("./sqlite_lib.php");

$page = $_POST["page"];
$row_cnt = $_POST["rows"];
$sort = $_POST["sort"];
$order = $_POST["order"];
$filter_name  = "";
if (array_key_exists("filter_name", $_POST)) {
    $filter_name  = $_POST["filter_name"];
}

$row_array = Array();

# file_put_contents("./a.txt", implode(",", array_values($_POST)) . "\n", FILE_APPEND);

$query_fields = Array("target_name", "book_name", "book_owner", "book_ctime", "book_size", "douban_url", "book_tags");
$rows = load_db_data($query_fields);
foreach ($rows as $row) {
    list($tname, $name, $owner, $ctime, $size, $douban, $tags) = $row;

    if ($filter_name != "" 
            && strpos(strtolower($name), strtolower($filter_name)) === false 
            && strpos(strtolower($tags), strtolower($filter_name)) === false 
            && strpos(strtolower($owner), strtolower($filter_name)) === false) {
        continue;
    }

    $file_path = urlencode("../books/$tname");
    $download_name = urlencode($name);
    if ($douban != "") {
        array_push($row_array, Array("book_name" => $name, "book_size" => $size, "book_owner" => $owner, "book_ctime" => $ctime, "operate" => "<a target='_blank' href='./php/download.php?file_path=$file_path&file_name=$download_name' class='download'>下载</a> <a data-tname='$tname' href='javascript:void(0);' class='delete' onClick='onDelete(this);'>删除</a> <a href='./php/book_edit.php?tname=$tname'>编辑</a>", "douban" => "<a href='$douban' target='_blank'>豆瓣</a>"));
    } else {
        array_push($row_array, Array("book_name" => $name, "book_size" => $size, "book_owner" => $owner, "book_ctime" => $ctime, "operate" => "<a target='_blank' href='./php/download.php?file_path=$file_path&file_name=$download_name' class='download'>下载</a> <a data-tname='$tname' href='javascript:void(0);' class='delete' onClick='onDelete(this);'>删除</a> <a href='./php/book_edit.php?tname=$tname'>编辑</a>", "douban" => ""));
    }
}

usort($row_array, "cmp");
if ($order == "desc") {
    $row_array = array_reverse($row_array);
}
$ret_arr = array_slice($row_array, ($page - 1) * $row_cnt, $row_cnt);

$ret_array = Array(
    "total" => count($row_array),
    "rows" => $ret_arr,
);

echo json_encode($ret_array);

function cmp($a, $b) {
    global $sort;
    $a_val = $a[$sort];
    $b_val = $b[$sort];
    if ($a_val == $b_val)
        return 0;
    return ($a_val < $b_val) ? -1 : 1;
}
