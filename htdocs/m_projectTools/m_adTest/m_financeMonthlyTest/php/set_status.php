<?php

$name = $_POST["name"];
$filename = md5($name);
#$name = "标记为月结待审核";

$cmd = "cd ../pyLib/; python ./bin/write.py '$filename'";
exec($cmd, $lines, $ret);
if ($ret == 0) {
    echo "数据库状态已成功更新为 \"$name\"";
} else {
    echo "数据库状态更新为 \"$name\" 失败";
}
