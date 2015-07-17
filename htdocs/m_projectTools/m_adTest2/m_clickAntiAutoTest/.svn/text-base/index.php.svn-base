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
<h2>点击反作弊测试自动化日志查看页面</h2>

<a id='readme' href="readme.html">readme</a>
&nbsp;
<a href="image.html">view framework</a>
<br><br>

<table id="entry_table" border=1>
    <tr>
        <th>执行时间</th>
        <th>测试描述</th>
        <th>用户</th>
        <th>日志查看</th>
    </tr>
<?php
$file_list = glob("./output/*.html");
sort($file_list);
foreach (array_reverse($file_list) as $file) {
    echo file_get_contents($file);
}
?>
</table>

<script src="./js/index.php.js"></script>
</body>
</html>
