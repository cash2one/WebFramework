<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>
<h2>财务系统对账程序测试页面</h2>
<br><br>

对账月份: <input id="acc_check" value="201302" size=6 /> <input type=button id="acc_check_btn" value="生成对账结果" />  <label id="info"></label>

<a id="manual_acc_check" href=''>手动运行对账</a>

<hr>
<br>

查看对账结果: 
<select>

<?php

function get_filenames($data_dir) {
    $filenames = Array();

    $handle = opendir($data_dir);
    while (false !== ($filename = readdir($handle))) {
        if (substr($filename, 0, 1) == ".") continue;
        array_push($filenames, $filename); 
    }
    return $filenames;
}

$data_dir = "./python/output";
$filenames = get_filenames($data_dir);
sort($filenames);
$filenames = array_reverse($filenames);
// array_splice($filenames, 20); // only show the newest 20 files

foreach ($filenames as $filename) {
    $title = substr($filename, 0, 4) . "年" . substr($filename, 4, 2) . "月";
    echo "<option id='$filename'>$title</option>\n";
}   
?>

</select>

<div id="content">
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
