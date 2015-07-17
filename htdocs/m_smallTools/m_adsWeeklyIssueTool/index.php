<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/index.php.css" type="text/css" />

    <script src="../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css" />
</head>

<body>

    <h2>广告测试 － 记录故障更容易!</h2>
    <hr>
    <br>

    LDAP: <input type="text" id="ldap">
    <br>
    <br>

    <table id="issues_table" border="1">
        <thead> 
            <tr>    
                <th>时间</th>
                <th>类别</th>
                <th>平台</th>
                <th>事件描述</th>
                <th>url</th>
                <th>严重级别</th>
                <th>原因及处理</th>
                <th>改进方案</th>
                <th>备注</th>
                <th><a href="" class="row_add head_row">添加</a></th>
            </tr>   
        </thead>

        <tbody id="issues_tbody"></tbody>
    </table>

    <br>
    <br>
    <input type="button" id="output" value="输出" />
    <a href="" id="go_to_issue_page">手动Copy完了, 粘贴到故障页</a>
    <hr>

    <div id="output_div" />
    <script charset="utf-8" src="./js/index.php.js" type="text/javascript"></script>
</body>

</html>
