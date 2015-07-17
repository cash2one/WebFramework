<html>

<head>
    <meta charset="utf-8" />

    <script src="../../../../js-base/jquery.min.js"></script>
    <script src="../../../../js-base/jquery-ui.min.js"></script>
    <!--
    <script src="../../../../js-base/easyui/jquery.easyui.min.js"></script>
    -->
    <script src="../../../../js-base/json.min.js"></script>
    <script src="../../../../js-base/angularJS/angular.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../../../../js-base/easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../../../js-base/easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../../../css-base/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
</head>

<body>
    <h3>广告线上故障查看页面</h3>

    <div id="header">
        开始时间: <input type="text" id="start_time" placeholder="以记录时间为准" />
        结束时间: <input type="text" id="end_time" placeholder="以记录时间为准" />
        故障类型: <select id="type">
                    <option value='all'>所有</option>
                    <option value='operation'>运维</option>
                    <option value='maintain'>运营</option>
                 </select>
        每页显示: <input type="text" id="itemsInPage" value="50" size=2 />
                  <input type="button" id="query" value="查询" />
    </div>

    <div id="infoBar">
    </div>

    <div id="content">
        <div id="pageInfo">
            <span>第3页/共252页</span>
            <a href="" id="pre">前一页</a>
            <a href="" id="next">后一页</a>
        </div>

        <table border='1'>
            <thead>
                <tr>
                    <th>登记时间</th>
                    <th>故障类型</th>
                    <th>故障标题</th>
                    <th>严重级别</th>
                    <th>原因及处理</th>
                    <th>改进方案</th>
                    <th>备注</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>

    <script src="./js/index.php.js"></script>
</body>
</html>
