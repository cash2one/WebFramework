<html>
<head>
    <meta charset="utf-8" />

    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
    <link rel="stylesheet" type="text/css" href="./css/showDiff.php.css" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />

    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/ldap_login.js"></script>
    <script src="../../js-base/json.min.js"></script>
</head>

<body>
    <h2>查看Svn日志，以及版本之间的差异</h2>

    <!-- control whether user input area show -->
    <hr> <a title="点击我来隐藏表格" class="open_close_tag" href="" id="user_input_a">&gt;&gt;&gt;</a>
    <br>
    <br>

    <table id="log_tbl" border="1">
        <thead>
            <tr>
                <th colspan="5">
                    SVN地址:
                    <input type=text id="svn_url_input" /> 
                    <input type=button id="query_log" value="查询Svn Log" title="点击来查看svn log的结果"/>
                    <input type=button id="query_diff" value="查询Svn Diff" title="选择两个版本，然后点击来看这两个版本之间的diff" disabled />
                </th>
            </tr>
            <tr>
                <th class='choice'>Checkbox</th>
                <th class='version'>Revision</th>
                <th class='user'>User</th>
                <th class='time'>Time</th>
                <th class='comment'>Comment</th>
            </tr>
        </thead>

        <tbody id="log-list">
        </tbody>
    </table>
    <br>

    <!-- show alert info -->
    <div id="log_info">
        <table border="1">
            <thead> 
                <tr><th colspan="4">用户查看</th></tr>
            </thead> 

            <tbody id="user-log"> 
            </tbody>
        </table>
    </div>

    <!-- control whether result area show -->
    <hr> <a href="" class="open_close_tag" id="show_result_a" title="点击我来隐藏Diff结果">&gt;&gt;&gt;</a>
    <br>
    <br>

    <div id="output_div"/>
    </div>

    <script src="./js/index.php.js"></script>
</body>

</html>
