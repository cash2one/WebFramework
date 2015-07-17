<html>

<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />
<link rel="stylesheet" type="text/css" href="./css/showDiff.php.css" />
<link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../js-base/jquery.min.js"></script>
<script src="../../js-base/jquery-ui.min.js"></script>
<script src="../../js-base/ldap_login.js"></script>
<script src="../../js-base/json.min.js"></script>
</head>

<body>
<h1 id="title">SVN-DIFF 工具</h1>

<!-- user input area -->
<div id="user_input_div">
    <table id="user_input_tbl">
        <tr>
            <td class="cell_name">代码版本1: </td>
            <td class="cell_value"><input type="text" id="svn_v1" /></td>
        </tr>
        <tr>
            <td class="cell_name">代码版本2: </td>
            <td class="cell_value"><input type="text" id="svn_v2" /></td>
        </tr>
        <tr>
            <td class="cell_name"></td>
            <td class="cell_value">
                <input type="button" id="request_btn" value="查看"/> &nbsp;&nbsp;
                <input type="checkbox" id="show_diff_detail">显示变更行Diff详情</input>
                <input type="checkbox" id="skip_test_files">忽略Java单元测试文件</input>
            </td>
        </tr>
    </table>
</div>
<br>

<!-- show alert info -->
<div id="log_info">
    <table border="1">
        <thead>
            <tr><th colspan="4">用户查看</th></tr>
        </thead> 
        <tbody>
        </tbody>
    </table>
</div>

<!-- show alert info -->
<div id="diff_result"></div>

<script src="./js/index.php.js"></script>
</body>

</html>
