<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/index.php.css" type="text/css" />

    <script src="../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../js-base/json.min.js" type="text/javascript"></script>
</head>

<body> 

    <h2>用例编写工具</h2>
    <hr>

    <!-- ################### case index div ############################# -->
    <div id="case_index">
        用户筛选: 
        <select id="user_filter">
            <option id="user_all">所有人</option>
        </select>
        <a href="" id="new_case_index_btn">新建</a><br><br>

        <table id="case_index_table" border="1">
            <thead>
                <tr>
                    <th>创建时间</th>
                    <th>用例集合标题</th>
                    <th>服务名称</th>
                    <th>创建者</th>
                    <th>提测ticket地址</th>
                    <th style="width:300px;">备注</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody id="case_index_tbody">
            </tbody>
        </table>
    </div>

    <!-- ################### new case index div ############################# -->
    <div id="new_case_index">
        <table border="1" id="new_index_table">
            <tr>
                <th>用例集合标题:</th>
                <td class='input'><input type=text id="case_index_title"></td>
            </tr>
            <tr>
                <th>服务名称:</th>
                <td class='input'><input type=text id="service_name"></td>
            </tr>
            <tr>
                <th>创建者:</th>
                <td class='input'><input type=text id="user"></td>
            </tr>
            <tr>
                <th>提测ticket地址:</th>
                <td class='input'><input type=text id="ticket_addr"></td>
            </tr>
            <tr>
                <th>备注:</th>
                <td class='input'><input type=input id="comment"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type=button id="new_index_btn" value="创建">
                    <input type=button id="new_index_return_btn" value="返回">
                </td>
            </tr>
        </table>
    </div>

    <!-- ################### edit case index div ############################# -->
    <div id="edit_case_index">
        <table border="1" id="edit_index_table">
            <tr>
                <th>用例集合标题:</th>
                <td class='input'><input type=text id='case_index_title2'></td>
            </tr>
            <tr>
                <th>服务名称:</th>
                <td class='input'><input type=text id='service_name2'></td>
            </tr>
            <tr>
                <th>创建者:</th>
                <td class='input'><input type=text id='user2'></td>
            </tr>
            <tr>
                <th>提测ticket地址:</th>
                <td class='input'><input type=text id='ticket_addr2'></td>
            </tr>
            <tr>
                <th>备注:</th>
                <td class='input'><input type=text id='comment2'></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type=button id="update_index_btn" value="更新">
                    <input type=button id="update_index_return_btn" value="返回">
                </td>
            </tr>
        </table>
    </div>

    <!-- ################### view list div ############################# -->
    <div id="case_list">
        <a href="" id="new_case_btn">新建</a>
        <a href="" id="save_case_list_btn">保存用例</a>
        <a href="" id="case_list_return_btn">返回</a>
        <br>
        <br>

        <table id='case_list_table' border='1'>
            <thead>
                <tr>
                    <th>用例分类名</th>
                    <th>用例标题</th>
                    <th>备注</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody id="case_list_tbody">
            </tbody>
        </table>

        <br>
        <a href="" id="show_case_list_as_wiki">以Wiki方式输出</a>
        <br>
        <br>
    </div>

    <!-- ################### wiki div ############################# -->
    <div id="show_as_wiki">
    </div>

    <!-- ################### new case div ############################# -->
    <div id="new_case">
        <table border="1" id="new_case_table">
            <tr>
                <th>用例分类名:</th>
                <td class='input'><input type=text id='case_cate'></td>
            </tr>
            <tr>
                <th>用例标题名:</th>
                <td class='input'><input type=text id='case_title'></td>
            </tr>
            <tr>
                <th>描述:</th>
                <td class='input'><textarea rows=20 id='case_comment'></textarea></td>
            </tr>
            <tr>
                <th>状态:</th>
                <td class='input'><input type=text id='case_status'></td>
            </tr>
            <tr>
                <td colspan=2>
                    <input type=button value="完成" id="keep_status_btn" />
                    <input type=button value="返回" id="new_case_return_btn" />
                </td>
            </tr>
        </table>
    </div>

    <script charset="utf-8" src="./js/index.php.js" type="text/javascript"></script>
</body>

</html>
