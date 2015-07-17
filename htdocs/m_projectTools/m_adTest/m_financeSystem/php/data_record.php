<html>
<head>
    <meta charset="utf-8">
</head>

<body>
    <div id="rd_head">
        <a href="" class="rd_link" id="set_senario">设定/选择测试版本</a>
        <a href="" class="rd_link" id="arch_status">存储/设定数据表状态</a>
        <a href="" class="rd_link" id="apply_status">存储数据表状态</a>
    </div> 
    
    <br>

    <div id="svn_set" class="rd_content">
        SVN: <select id="svn_select"></select>
        描述: <select id="senario_select"></select>
        <a href="" id="svn_select_btn">选取</a>
        <a href="" id="svn_add_btn">添加</a>
        <a href="" id="svn_copy_btn">复制</a>
        <a href="" id="svn_del_btn">删除</a>
    </div>

    <div id="rd_content_set" class="rd_content">
        <table>
            <tr><td colspan="2"><a href="" id="arch_status_btn">备份数据表状态</a></td></tr>
            <tr><td>当前数据表状态:</td><td id="current_status"></td></tr>
            <tr>
                <td id="rd_table_list">
                </td>
                <td>
                    <tbody id="status_list"></tbody>
                </td>
            </tr>
        </table>
    </div>

    <div id="rd_content_apply" class="rd_content">
        <table>
            <tbody id="unset_status_name_list">
            </tbody>
        </table>
    </div>

    <script src="./js/data_record.php.js"></script>
</body>
</html>
