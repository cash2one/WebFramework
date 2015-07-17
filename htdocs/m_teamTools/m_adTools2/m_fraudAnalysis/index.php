<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="../../../m_testAssist/m_ProcessPerfMonitor/css/examples.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>

<script src="../../../m_testAssist/m_ProcessPerfMonitor/js/flot/jquery.flot.min.js"></script>
<script src="../../../m_testAssist/m_ProcessPerfMonitor/js/flot/jquery.flot.time.min.js"></script>
</head>

<body>

<h2>反作弊日志数据统计</h2>
-- 数据来源于线上3台机器的反作弊日志(<a target='_old' href='./index2.php'>老版本</a>)<br><br>

<div id="head">
<?php

$platformDict = Array(
    "DSP" => "智选",
    "EADC" => "频道",
    "EADM" => "邮箱",
    "EADU" => "联盟",
    "EADS" => "搜索",
    "EADD" => "词典",
    "OFFLINE" => "线下直销",
);

    $plat_names = glob("result_data/*");
    foreach ($plat_names as $platname) {
        $platname = basename($platname);
        $platname2 = $platformDict[$platname];
        echo "<a class='plat' href='' id='$platname'>$platname2</a>&nbsp;";
    }

    echo "&nbsp;";
    echo "<input type=radio id='daily' name='radio' checked />显示天级别";
    echo "&nbsp;";
    echo "&nbsp;";
    echo "<input type=radio id='hourly' name='radio' />显示小时级别";
    echo "&nbsp;";
    echo "<input type=checkbox id='select_all' checked />全选";
?>
</div>

<br>

<div id="content">
    <div class="demo-container">
        <div id="placeholder" class="demo-placeholder" style="float:left; width:87%;"></div>
        <p id="choices" style="float:right; width:13%;"></p>
    </div>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
