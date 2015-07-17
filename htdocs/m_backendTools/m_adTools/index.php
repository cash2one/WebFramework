<html>
<head>
    <meta charset="utf-8" />

    <link rel="stylesheet" type="text/css" href="../../js-base/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../../css-base/grid.css" />
    <link rel="stylesheet" type="text/css" href="../../js-base/easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../js-base/easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../js-base/easyui/demo/demo.css" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />

    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/ldap_login.js"></script>
    <script src="../../js-base/json.min.js"></script>
    <script src="../../js-base/easyui/jquery.easyui.min.js"></script>
    <script src="../../js-base/bootstrap/js/bootstrap.min.js"></script>
    
    <style>
        h3 {display:inline}
    </style>
</head>

<body style="background-color:#effaff;">
    <h4>广告后台常用工具平台</h4>
    <a target="_blank" href="./readme.php">readme</a>
    <div style="height:10px;" name="just-for-row-space"></div>
    <div class="yui3-g">
        <div class="yui3-u-1-4 mycontainer" id="file_index">
            <?php
                include("./php/showTree.php");
                $ret_array = Array();
                array_push($ret_array, '<ul id="tt" class="easyui-tree">');
                get_tree(".", $ret_array);
                array_push($ret_array, '</ul>');
                echo implode("\n", $ret_array);
            ?>
        </div>

        <div class="yui3-u-3-4 mycontainer" id="file_content" style="background:#f5f5f5">
        </div>
    </div> 

    <script>
    $("#tt").tree({
        onClick: function(node) {
            var index_tts_file = node.attributes["file_path"];
            var tool_name      = node.attributes["tool_name"];
            $("div#file_content").html("").load("./php/fileContent.php", {"file_path":index_tts_file, "tool_name": tool_name});
        },
    });
    </script>
</body>

</html>
