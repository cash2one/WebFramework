<?php

$name = $_POST["name"];
$filename = md5($name);

$html_data_dir = "../html_data";
$cmd = "rm $html_data_dir/case_*.$filename.html";
exec($cmd, $lines, $ret);
if ($ret == 0) {
    // echo "删除本地备份状态('$name')成功";

} else {
    echo "删除本地备份状态('$name')失败";
}
