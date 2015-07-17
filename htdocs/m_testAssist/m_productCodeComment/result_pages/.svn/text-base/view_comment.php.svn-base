<html>

<head>
    <meta charset="utf-8" />

    <script src="../../../js-base/jquery.min.js"></script>
    <script src="../../../js-base/jquery-ui.min.js"></script>
    <script src="../../../js-base/easyui/jquery.easyui.min.js"></script>
    <script src="../../../js-base/json.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../../css-base/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/result_pages/view_comment.php.css" />

    <?php
        include(dirname(__FILE__) . "/../php/dbLib.php");
        include(dirname(__FILE__) . "/../php/commonLib.php");
        if (!array_key_exists("prodName", $_POST) || !array_key_exists("version", $_POST) || !array_key_exists("userName", $_POST)) {
            echo "Error: Lack of Valid Post Parameters!";
            return;
        }

        $prodName = trim($_POST["prodName"]);
        $version  = $_POST["version"];
        $user     = $_POST["userName"];
        echo "<script>var _prodName = '$prodName'; var _version = '$version'; var _userName = '$user';</script>";
    ?>
</head>

<body>
    <h3 id="title">代码阅读 - <?php echo $prodName, "@", $version; ?> - <?php echo $user; ?></h3>
    <div id="title2">
        <input type=checkbox id="note_add_mode"/>注释模式
    </div>

    <div id="filePath">
        文件路径: <span></span>
    </div>

    <div id="content">
        <div id="tree_content" class="sub">
            <?php 
                $svnAddr = "";
                $codeInfoArr = dbUtil(LoadCodePathInfoTable);
                foreach ($codeInfoArr as $subArr) {
                    if ($subArr["prodName"] == $prodName && $subArr["version"] == $version) {
                        $svnAddr = $subArr["svnPath"];
                        break;
                    }
                }
                if ($svnAddr == "") {
                    echo "Error: Can't find svn Path based on($prodName:$version)"; 
                } else {
                    $svnDirPath = "../code/" . getCodeDirPath($prodName, $svnAddr);
                    $lines = getTreeHtmlLines(realpath($svnDirPath), "tt");
                    echo implode("\n", $lines);
                }
            ?>
        </div>

        <div id="tree_navbar" class="sub view1">
            <?php $link_name = "显示目录树"; echo "<a href='' id=''>$link_name</a>"; ?>
        </div>

        <div id="file_content" class="sub view1">
        </div>

        <div id="note_content" class="sub view1">
            <div id="n_view" class="n_sub">
                <?php include("../php/tools/viewNote.php"); ?>
            </div>
            <div id="n_add" class="n_sub">
                <?php include("../php/tools/addNote.php"); ?>
            </div>
        </div>
    </div>

    <script src="../js/result_pages/view_comment.php.js"></script>
</body>
</html>
