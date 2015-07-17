<html>
    <head>
        <meta charset="utf-8"/>
        <script src="../../../../js-base/jquery.min.js"></script>
        <?php
            include "api.php";
        ?>
        <style>
        </style>
    </head>
    <body>
        <h3>白名单查看</h3>
        <div id="info">
            <label>已有白名单：</label>
            <label>
            <?php
                $whitelistNames = getListNames();
                sort($whitelistNames);
                foreach (array_reverse($whitelistNames) as $whitelistName) {
                    echo $whitelistName,"\t";
                }
            ?>
            </label>
            <br/>
            <br/>
            <label>输入白名单名称：</label>
            <input type="text" id="listName_view">
            <button type="button" id="view_content">查看白名单内容</button>
            <button type="button" id="new_whitelistName">新增白名单</button>
            <br>
            <label id="contents">
            </label>
        </div>
        <br/>
        <br/>
        <hr/>
        <h3>白名单编辑</h3> 
        <div id="add_new_div">
            <label>选择白名单：</label> 
            <select id="whitelistNames_edit">
                <?php
                    $whitelistNames = getListNames();
                    sort($whitelistNames);
                    foreach (array_reverse($whitelistNames) as $whitelistName) {
                        echo "<option>$whitelistName</option>";
                    }
                ?>
            </select>
            <br/>
        </div>
        <div>
            <label>内容：</label>
            <input id="whitelistContent" size="30" >
            <button type="button" id="add_content" >增加内容</button>
            <button type="button" id="delete_content">删除内容</button>
        </div>


        <script src="../js/whitelist.php.js"></script>
    </body>
</html>
