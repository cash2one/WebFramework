<html>
<head>
<meta charset="utf-8">
</head>

<body>
<?php

echo "<a href='../index.php'>返回</a><br><br>";

$log_ts = $_GET["ts"];
$log_dir = "../output/$log_ts";
$prod_list = Array(
    "smokeTest",
    "commonTest",
    "channelTest",
    "dictTest",
    "dspTest",
    "mailTest",
    "offlineTest",
    "searchTest",
    "unionTest"
);

echo "<table border=1>\n";
echo "<tr><th>平台</th><th>用例标题</th><th>用例描述</th><th>执行结果</th><th>结果描述</th><th>查看细节</th></tr>\n";

foreach ($prod_list as $prod_dir) {
    $dir_path = "$log_dir/$prod_dir";
    $index_file_list = glob("$dir_path/*.index");
    sort($index_file_list);
    foreach (array_reverse($index_file_list) as $filename) {
        echo file_get_contents($filename);
    }
}

echo "</table>\n";

?>

</body>
</html>
