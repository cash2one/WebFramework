<html>
<head>
    <meta charset="utf-8">
</head>

<body>
    <div id="qd_head">
        广告商ID:  <input type=text id="sponsor_id" />
        <a href="" class="query_table" id="query_data">查询</a>
        <a href="" class="delete_table" id="delete_data">删除</a>
        <input type="checkbox" id="select_all" />全选
        <span class="query_table"><input type="checkbox" id="use_readable" />使用可读性</span>

        <br><br>

        <?php
            system("cd ../python; python web-tools/query_delete_tbl.py");
        ?>
    </div>

    <div id="qd_results">
    </div>

    <script src="./js/query_delete_tbl.php.js"></script>
</body>
</html>
