<?php

$name = $_POST["name"];
$filename = md5($name);
$status_list_dir = "./status-list";
$ret = unlink("$status_list_dir/$filename");
if ($ret == true) {
    // echo "删除状态名('$name')成功";
} else {
    echo "删除状态名('$name')失败";
}
