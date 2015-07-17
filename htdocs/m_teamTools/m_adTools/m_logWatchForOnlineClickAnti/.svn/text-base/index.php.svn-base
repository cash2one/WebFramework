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

<!-- header bar -->
<center>
<a href="" title="用户选项" id="show_machine_info">&lt;&lt;&lt;</a>
<h2>线上点击反作弊测试日志查看</h2>
<a href="" title="结果查看" id="show_log_info">&gt;&gt;&gt;</a>
</center>

<!-- user input area -->
<div id="input_area">
    <table border="1" align="center"> 
    <thead>
        <tr>
            <th>服务</th>
            <th>机器及日志路径</th>
            <th>日志过滤条件</th>
            <th>最大读取行数</th>
        </tr>
    </thead>
    <tbody id="input_tbody">
        <tr id="click_tr">
            <td><input type=checkbox for="点击服务" id="click_service" class="service_type" /> 点击服务</td>
            <td>
                <span>nb011:/disk4/eadop/click-resin/logs/log</span><br>
                <span>nb015:/disk4/eadop/click-resin/logs/log</span><br>
                <span>nc092:/disk4/eadop/click-resin/logs/log</span><br>
                <span>nc072:/disk4/eadop/click-resin/logs/log</span><br>
            </td>
            <td rowspan="2">
                <input type=text id="log_filter_str" value="imprIp=61.135.255.83" /> 
            </td>
            <td rowspan="2">
                <input type=text id="max_log_count" value="1000" /> 
            </td>
        </tr>
        <tr id="anti_tr">
            <td><input type=checkbox for="反作弊服务" id="anti_service" class="service_type" /> 反作弊服务</td>
            <td>
                <span>hs026:/disk2/eadop/antifrauder/logs/log</span><br>
                <span>hs027:/disk2/eadop/antifrauder/logs/log</span><br>
                <span>nc096:/disk2/eadop/antifrauder/logs/log</span><br>
            </td>
        </tr>
        <tr>
            <td colspan=4">
                LDAP: <input type=text id="ldap_text" /> 
                集群密码: <input type=password id="machine_text" /> 
                <!--
                rsa密码(如果已设): <input type=password id="machine_rsa" />
                -->
            </td>
        </tr>
        <tr>
            <td colspan=4 style="text-align:center;">
                <input type=button id="query_log" value="请求日志" />
                <input checked type=checkbox id="clearLogFlag" for="请求之前清除日志" />请求之前清除日志
            </td>
        </tr>
    </tbody>
    </table>

    <br>

    <table border="1" align="center">
        <thead>
            <tr>
                <th>时间</th>
                <th>用户</th>
                <th>过滤串</th>
            </tr>
        </thead>
        <tbody id="log_tbody">
        </tbody>
    </table>

</div>

<!-- output area -->
<div id="output_area">
    <table>
    <thead>
        <tr>
            <th><a href="" id="click_log_show">点击日志</a></th>
            <th><a href="" id="anti_log_show">反作弊日志</a></th>
        </tr>
    </thead>
    </table>

    <div id="click_log_output_div" class="log_content_div">
    </div>

    <div id="anti_log_output_div" class="log_content_div">
    </div>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
