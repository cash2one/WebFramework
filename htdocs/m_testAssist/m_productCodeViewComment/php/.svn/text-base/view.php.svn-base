<html>

<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/jquery.min.js"></script>
    <script src="../../../js-base/jquery-ui.min.js"></script>
    <script src="../../../js-base/json.min.js"></script>
    <script src="../../../js-base/easyui/jquery.easyui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../../css-base/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/view.php.css" />
    <style>
        a {text-decoration: none; color: blue; font-size: 0.8em}
        a:hover {text-decoration: underline}
    </style>
</head>

<body>
<?php
    $prodName = @$_POST["prod"];
    $version  = @$_POST["ver_name"];

    if ($prodName == null|| $version == null) {
        echo "Error: Invalid POST parameters ", "<a href='../index.php'>返回</a>";
        return;
    }

    include("./util/util.php");
    $disk_path = dbUtil(GetCodeDiskPath, $prodName, $version);
?>

<div id="header" style="margin-bottom: 10px; margin-left:10px">
    <h3 style="display:inline"><?php echo $prodName ?> - 代码阅读</h3> 
    <a id="return" href="../index.php">返回首页</a>
</div>

<hr style="border: 1px solid #DCDCDC">

<div id="file_tree" class="assist">
    <?php echo getTreeHtmlStr($disk_path); ?>
</div>

<table style="width:100%; height:95%;">
    <tr>
        <td style="width:10px" id="src_tree_td">
            <a id="src_tree" href="">文<br>件<br>树</a>
        </td>
        <td>
            <div id="file_path">文件路径：</div>
            <div id="file_content" class="content" style="background:#c5ffc4">
            </div>
        </td>
        <td style="width:15px" id="note_td">
            <a id="note" href="">注<br>释</a>
        </td>
    </tr>
</table>

<div id="file_note" class="assist">
    <?php include("./util/noteInfo.php"); ?>
</div>

<script src="../js/view.php.js"></script>
</body>

</html>
