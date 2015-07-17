<?php


// from: http://www.cnblogs.com/chenwenbiao/archive/2011/08/01/2123905.html

$name = $_POST["status_name"];
$filename = md5($name);
$status_list_dir = "./status-list";
$file = "$status_list_dir/$filename";
file_put_contents($file, $name);

$cmd = "cd ../pyLib/; python ./bin/read.py '$filename'";
exec($cmd, $lines, $ret);
if ($ret == 0) {
    // echo "备份数据库状态($name)成功";
} else {
    echo "备份数据库状态($name)失败";
}
