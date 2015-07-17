<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />
<link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../js-base/jquery.min.js"></script>
<script src="../../js-base/jquery-ui.min.js"></script>
<script src="../js-base/json.min.js"></script>
</head>

<body>
<table border="1" align="center">
<tr><th colspan=5">后端自动部署日志</th><th><a target="_blank" href="./logs/manual.html">API手册</a></th><th><a target="_blank" href="https://dev.corp.youdao.com/outfoxwiki/Test/AD/zhangpei/AutoDeploy">Wiki</a></th></tr>
<tr><th>开始部署时间</th><th>用户名</th><th>部署名称</th><th>部署类型</th><th>部署机器</th><th>步骤链接</th><th>日志链接</th></tr>
<?php
    $log_dirs =array_reverse(glob("./logs/log_*_*"));
    foreach($log_dirs as $log_dir){
        $file = $log_dir . "/log.html";
        $file_content = file_get_contents($file);
        echo $file_content;
    }
?>
</table>

<script src="./js/index.php.js"></script>
</body>
</html>
