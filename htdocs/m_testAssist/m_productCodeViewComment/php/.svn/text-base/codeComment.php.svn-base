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

    <?php
        $user = "zhangpei";
        $codeDir = "/disk2/zhangpei/financial-system/financial-war";
    ?>
</head>

<body style="background-color:#effaff;">
    <h4>产品代码阅读及注释工具</h4> <!-- ?php echo $user ? -->
    <div style="height:10px;" name="just-for-row-space"></div>
    <div class="yui3-g">
        <div class="yui3-u-1-5 mycontainer" id="file_index">
            <?php
                include("php/myUtil.php");
                echo getTreeHtmlStr($codeDir);
            ?>
        </div>

        <div class="yui3-u-1-2 mycontainer" id="file_content" style="background:#C5FFC4">
            文件内容将显示在这里...
        </div>

        <div class="yui3-u-1-4 mycontainer" id="file_comment">
            <?php
                include("php/file_comment.php");
            ?>
        </div>
    </div> 

    <script src="./js/index.php.js"></script>
</body>

</html>
