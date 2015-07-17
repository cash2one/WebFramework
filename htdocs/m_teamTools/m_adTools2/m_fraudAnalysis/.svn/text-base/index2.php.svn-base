<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index2.php.css" />
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>

<h2>反作弊日志数据统计</h2>
-- 数据来源于线上3台机器的反作弊日志<br><br>

<div id="head">
请选择查看日期
<select id="date_select">
    <option>数据汇总</option>
<?php
    $filelist = glob("./data/*.log.html");
    sort($filelist);
    # 只显示最近30天的日志
    foreach (array_splice(array_reverse($filelist), 0, 30) as $file) {
        $filename = basename($file);
        $showname = substr($filename, 0, 4) . "年" . substr($filename, 4, 2) . "月" . substr($filename, 6, 2) . "日";
        echo "<option id='$file'>$showname</option>";
    }
?>
</select>
</div>

<br>

<div id="log_table">
</div>

<script src="./js/index2.php.js"></script>
</body>
</html>
