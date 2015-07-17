<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/index.php.css" type="text/css" />

    <script src="../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../js-base/json.min.js" type="text/javascript"></script>
</head>

<body> 

    <h2>产品代码笔记</h2>
    <hr>

    <!-- ################### case product div ############################# -->
    <div id="product_index_div">
        <a href="" id="new_product_index_btn">新建</a><br><br>
        <table id="product_index_table" border="1">
            <thead>
                <tr>
                    <th>创建时间</th>
                    <th>产品名</th>
                    <th>创建者</th>
                    <th>SVN地址</th>
                    <th>备注</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody id="product_index_tbody">
            </tbody>
        </table>
    </div>

    <!-- ################### new product index div ############################# -->
    <div id="new_index_div">
        <table border="1" id="new_index_table">
            <tr>
                <th>产品名:</th>
                <td class='input'><input type=text id="prod_index_title"></td>
            </tr>
            <tr>
                <th>创建者:</th>
                <td class='input'><input type=text id="index_author"></td>
            </tr>
            <tr>
                <th>SVN地址:</th>
                <td class='input'><input type=text id="svn_addr"></td>
            </tr>
            <tr>
                <th>备注:</th>
                <td class='input'><input type=text id="comment"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type=button id="new_index_btn" value="创建">
                    <input type=button id="new_index_return_btn" value="返回">
                </td>
            </tr>
        </table>
    </div>

    <!-- ################### edit product index div ############################# -->
    <div id="update_index_div">
        <table border="1" id="update_index_table">
            <tr>
                <th>产品名:</th>
                <td class='input'><input type=text id="prod_index_title2"></td>
            </tr>
            <tr>
                <th>创建者:</th>
                <td class='input'><input type=text id="index_author2"></td>
            </tr>
            <tr>
                <th>SVN:</th>
                <td class='input'><input type=text id="svn_addr2"></td>
            </tr>
            <tr>
                <th>备注:</th>
                <td class='input'><input type=text id="comment2"></td>
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
    <div id="note_list_div">
        <a href="" id="new_note_btn">新建</a>
        <a href="" id="note_list_return_btn">返回</a>
        <br>
        <br>

        <table id='note_list_table' border='1'>
            <thead>
                <tr>
                    <th>更新时间</th>
                    <th>标题</th>
                    <th>贡献者</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody id="note_list_tbody">
            </tbody>
        </table>

        <br>
    </div>

    <!-- ################### new note div ############################# -->
    <div id="new_note_div">
        <table border="1" id="new_note_table">
            <tr>
                <th>标题:</th>
                <td class='input'><input type=text id='note_title'></td>
            </tr>
            <tr>
                <th>描述:</th>
                <td class='input'><textarea rows=30 id='note_content'></textarea></td>
            </tr>
            <tr>
                <th>贡献者:</th>
                <td class='input'><input type=text id='note_author'></td>
            </tr>
            <tr>
                <td colspan=2>
                    <input type=button value="保存" id="save_note_btn" />
                    <input type=button value="返回" id="new_note_return_btn" />
                </td>
            </tr>
        </table>
    </div>

    <!-- ################### update note div ############################# -->
    <div id="update_note_div">
        <table border="1" id="update_note_table">
            <tr>
                <th>标题:</th>
                <td class='input'><input type=text id='note_title2'></td>
            </tr>
            <tr>
                <th>描述:</th>
                <td class='input'><textarea rows=30 id='note_content2'></textarea></td>
            </tr>
            <tr>
                <th>贡献者:</th>
                <td class='input'><input type=text id='note_author2'></td>
            </tr>
            <tr>
                <td colspan=2>
                    <input type=button value="保存" id="save_note_btn2" />
                    <input type=button value="返回" id="update_note_return_btn" />
                </td>
            </tr>
        </table>
    </div>

    <script charset="utf-8" src="./js/index.php.js" type="text/javascript"></script>
</body>

</html>
