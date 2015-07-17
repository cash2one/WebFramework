<html>
<head>
    <meta charset="utf-8" />

    <link rel="stylesheet" type="text/css" href="./css/index2.php.css" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />

    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/ldap_login.js"></script>
    <script src="../../js-base/json.min.js"></script>
</head>

<body>
    <h2>性能测试 － Vaquero 结果查看</h2>

    <hr> <a title="点击我隐藏输入部分" href="" id="input_a">&lt;&lt;&lt;</a>
    <div id="user_input_area">
        <!-- analyzer input table -->
        <table id="user_input_table" border="1">
            <thead>
                <tr>
                    <th colspan="6">所需Analyzer的信息输入</th>
                </tr>
                <tr>
                    <th class='hostname'>机器名</th>
                    <th class='ana_path'>Analyzer路径</th>
                    <th class='product'>Product</th>
                    <th class='serv_type'>ServerType</th>
                    <th class='cub_id'>CubId</th>
                    <th class='op'><a href="" class="add_row head_add">添加行</a></th>
                </tr>
            </thead>

            <tbody id="analyzer_input">
            </tbody>
        </table>

        <br>

        <!-- other info of machine input table -->
        <table border="1" id="machine_info_table">
        </table>

        <br>
        <table border="1">
            <tr>
                <td>
                    <!-- make log table visiable or hidden link button -->
                    <a href="" id="view_extra_info">显示额外信息</a>
                </td>
                <td>
                    <!-- make log table visiable or hidden link button -->
                    <a href="" id="view_log">查看日志</a>
                </td>
                <td>
                    <!-- time selection area -->
                    <select id="time_type">
                        <option>Hour</option>
                        <option>8 Hours</option>
                        <option>Day</option>
                        <option>Week</option>
                        <option>Month</option>
                        <option>Manual</option>
                    </select>
                    <input type="text" disabled id="start_tm" class="manual_time" />
                    &nbsp;-&nbsp;
                    <input type="text" disabled id="end_tm" class="manual_time" />
                </td>
                <td>
                    <p class="small">每行显示：</p>
                    <select id="count_select">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option selected>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                    </select>
                </td>
                <td>
                    <!-- request result button -->
                    <a href="" id="show_results">显示结果</a>
                </td>
            </tr>
        </table>
        <br>

        <!-- log info show table -->
        <div id="log_info">
            <table id="log_info_table" border="1">
            <thead>
                <tr>
                    <th colspan="6">用户日志</th>
                </tr>
                <tr>
                    <th class='hostname'>机器名</th>
                    <th class='ana_path'>Analyzer路径</th>
                    <th class='product'>Product</th>
                    <th class='serv_type'>ServerType</th>
                    <th class='cub_id'>CubId</th>
                    <th class='op'>操作</th>
                </tr>
            </thead>
            
            <tbody id="log_info_tbody">
            </tbody>
            </table>
        </div>
    </div>

    <hr> <a title="点击我隐藏结果部分" href="" id="output_a">&lt;&lt;&lt;</a>
    <div id="result_show_area">
    </div>

    <script src="./js/index2.php.js"></script>
</body>

</html>
