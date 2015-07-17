<?php

include("./sqlite_lib.php");

$db = get_db();
$result = sqlite_query($db, "select book_owner, count(book_owner) from book_info where book_deleted = '0' group by book_owner", SQLITE_NUM);
$rows = sqlite_fetch_all($result);

$ret_array = Array();
foreach ($rows as $row) {
    list($user, $count) = $row;
    array_push($ret_array, Array($count, "$user"));
}

echo json_encode($ret_array);
