<html>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/jquery.min.js"></script>
    <script src="../../../js-base/json.min.js"></script>
    <script src="../../../js-base/easyui/jquery.easyui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../css/comment.php.css" />
    <style>
        a {text-decoration:none; color:blue; font-size: 0.8em}
        a:hover {text-decoration:underline}
    </style>
</head>

<body>
    <?php
        $prodName = @$_POST["prod"];
        $version  = @$_POST["ver"];
        $commentor = @$_POST["commentor"];

        if ($prodName == null || $version == null || $commentor == null) {
            echo "Error: Invalid post parameters ", "<a href='../index.php'>返回</a>";
            return;
        }

        include("./util/util.php");
        $disk_path = dbUtil(GetCodeDiskPath, $prodName, $version);
    ?>

<div id="header" style="margin-bottom: 10px; margin-left:10px">
    <h3 style="display:inline"><?php echo $prodName ?> - 代码备注</h3> 
    <a id="src_code" href="">源码</a>
    <a id="note" href="">注释</a>
    <a id="return" href="../index.php">返回首页</a>
</div>

<hr style="border: 1px solid #DCDCDC">

<div class="yui3-g">
    <div class="yui3-u-1-5">
        <?php echo getTreeHtmlStr($disk_path); ?>
    </div>

    <div class="yui3-u-4-5" id="file_content" style="background:#c5ffc4; z-index=0">
        <div id="src_code">
        </div>
    </div>

    <div class="yui3-u-4-5" id="file_note" style="background:#c5ffc4; z-index=10">
    </div>
</div>

<script src="../js/comment.php.js"></script>
</body>
</html>
