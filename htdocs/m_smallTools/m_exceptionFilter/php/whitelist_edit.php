<html>
    <head>
        <meta charset="utf-8"/>
        <script src="../../../../js-base/jquery.min.js"></script>
        <?php
            include("./getWhitelist.php");
        ?>
        <style>
        </style>
    </head>
    <body>
        <h1>View 白名单</h1>
        <label>白名单列表：</label>
        <?php
            include("./loadWhitelistNames.php");
        ?>
        <label>Content：</label>
        <table id="contents">
        
        </table>
        <div>
            <input type="radio" name="add_new_whitelist" value="增加新白名单" checked>创建新白名单
            <input type="radio" name="edit_whitelist" value="修改已有白名单" >修改已有白名单
            <input type="radio" name="delete_whitelist" value="删除已有白名单" >删除已有白名单
        </div>
        <hr/>
        <div id="add_new_div">
            <label>白名单名称:</label> 
            <input type="text" id="new_whitelist_name">
            <label>需过滤的异常:(正则表达式，一行一个。如：*bucket* 则表示过滤掉所有bucket相关的异常)</label>
            <br/>
            <textarea id="whitelistContent" cols="160" rows="10" ></textarea>
            <br/>
            <button id="add_new_whitelist" type="button" >添加</button>
        </div>
        <div id="edit_div">
            <label>选择白名单:</label>
            <select id="whiteList">
                <option>none</option>
            </select> 
        </div>


        <script src="../js/whitelist_edit.php.js"></script>
    </body>
</html>
