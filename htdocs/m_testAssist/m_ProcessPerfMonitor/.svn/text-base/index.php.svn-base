<html>
<head>
    <meta charset="utf-8" />

    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="./css/examples.css" type="text/css" media="all" />

    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/ldap_login.js"></script>
    <script src="../../js-base/json.min.js"></script>
    <script src="./js/flot/jquery.flot.min.js"></script>
    <script src="./js/flot/jquery.flot.time.min.js"></script>
</head>

<body>
    <h2>测试机Java进程资源查看</h2>
    <br>

    <table>
    <tr>
    <td>
    选择机器:
    <select id="machine_list">
    <?php
        $options = Array();
        $handle = opendir('perf_results');
        while (false !== ($file = readdir($handle))) {
            if (substr($file, 0, 1) == ".") continue;
            array_push($options, "<option>$file</option>");
        }
        closedir($handle);
        
        sort($options);
        echo implode("\n", $options);
    ?>
    </select>
    </td>
    <td>

    &nbsp;&nbsp;选择日期:
    <select id="date_list"></select>
    </td>
    <td>

    &nbsp;&nbsp;选择进程(pid-user):
    <select id="process_list"></select>
    </td>
    <td>
    &nbsp;&nbsp;    
    <input type="checkbox" id="ignore_vaquero" checked>忽略Vaquero进程
    </td>
    <td>
    &nbsp;&nbsp;    
    <a href="" id="refresh">刷新</a>
    </td>
    <td>
    &nbsp;&nbsp;
    <input type="checkbox" id="select_all" checked>全选
    </td>
    <td>
    &nbsp;&nbsp; 
    <a href="" id="flot-filter">设置阈值</a>
    &nbsp;
    <span id="filter"> 
        Min: <input type=text id="min_value" size=6 /> &nbsp; 
        Max: <input type=text id="max_value" size=6/>&nbsp;
        <a href="" id="apply">应用</a>
    </span>
    </td>
    <tr>
    </table>

    <div id="content">
        <div class="demo-container">
            <div id="placeholder" class="demo-placeholder" style="float:left; width:89%;"></div>
            <p id="choices" style="float:right; width:11%;"></p>
        </div>
        <p id="cmd"></p>
    </div>

    <script src="./js/index.php.js"></script>
</body>

</html>
