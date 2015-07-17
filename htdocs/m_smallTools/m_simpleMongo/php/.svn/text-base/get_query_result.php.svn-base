<?php

$host = $_POST["host"];
$port = $_POST["port"];
$user = $_POST["user"];
$password = htmlspecialchars($_POST["password"], ENT_QUOTES);
$password = str_replace("&quot;", '"', $password);
$dbname = $_POST["dbname"];
$table = $_POST["table"];
$query_str = htmlspecialchars($_POST["query_str"], ENT_QUOTES);
$query_str = str_replace("&quot;", '"', $query_str);
$start_index = (int)$_POST["start_index"];
$count = (int)$_POST["count"];

if ($count <= 0) {
    $count = 10;
} elseif ($count > 1000) {
    $count = 1000;
}

# 注意：&#039; 需要转化为', $start_index从1开始
# 注意：值是str(1) 表示字符串1， 也即"1"
$cmd = "./python/get_query_result.py '$host' '$port' '$user' '$password' '$dbname' '$table' '$query_str' '$start_index' '$count'";
exec($cmd, $output, $ret);
file_put_contents("abc.sh", $cmd);
if ($ret != 0) {
    echo "请求数据失败!<br>";
} else {
    echo implode("", $output);
}
