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
<h2>Web服务测试 - 数据库状态备份及比对工具</h2>

<div id="web_config">
    <table border='1'>
        <tr>
            <th>配置列表</th>
            <th>配置内容</th>
        </tr>
        <tr>
            <td id="conf_list"></td>
            <td rowspan='2' id="conf_content"></td>
        </tr>
        <tr>
            <td><input type=button id="set_conf_btn" value="应用" /></td>
        </tr>
    </table>
</div>

<div id="main_page">
    <div id="title_list">
        <a href="" id="show_table_t">显示数据库数据</a>
        <a href="" id="del_table_t">删除数据库数据</a>
        <a href="" id="record_db_t">录制数据库状态</a>
        <a href="" id="show_db_t">数据库状态查看</a>
    </div>

    <br>
    <hr>

    <div id="show_table" class="content">
        <div id="table_list_for_show" ></div>
        <br>
        <input type=button id="show_table_btn" value="查看结果" />
        <input type=checkbox id="check_all_1"> 选中所有
    </div>

    <div id="del_table" class="content">
        <div id="table_list_for_del" ></div>
        <br>
        <input type=button id="del_table_btn" value="清空表" />
        <input type=checkbox id="check_all_2"> 选中所有
    </div>

    <div id="record_db" class="content">
        当前状态：<span id=cur_state></span>
        <table border='1' id="record_db_tbl">
            <thead>
            <tr>
                <th>状态名</th>
                <td>
                    <a id="bakup_state" href="">备份数据</a>
                    <a id="del_all_states" href="">删除所有</a>
                </td>
            </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>

    <div id="show_db" class="content">
        场景一: <select id="senario_1"></select>
        <br>
        场景二: <select id="senario_2"></select>
    </div>

    <hr>

    <div id="div_result">
    </div>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
