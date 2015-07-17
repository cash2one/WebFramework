<html>

<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="../js-base/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/newOrEdit.php.css" />

    <script src="../js-base/jquery.min.js"></script>
    <script src="../js-base/jquery-ui.min.js"></script>
    <script src="../js-base/bootstrap/js/bootstrap.min.js"></script>
<?php
    include("./sqlite_lib2.php");
    
    if (! array_key_exists("type", $_GET)) {
        echo "错误，缺少请求类型!";
        return;
    }

    $type = $_GET["type"];
    if ($type != "new" && $type != "edit") {
        echo "错误，无效的请求类型($type)!";
        return;
    }

    if ($type == "edit" && !array_key_exists("id", $_GET)) {
        echo "错误，没有指定任何id来编辑!";
        return;
    }

    $id = "";
    $row = Array("", "", "", "", "", "", "", "", "", "", "", "");
    if (array_key_exists("id", $_GET)) {
        $id = $_GET["id"];
        $row = load_project($id);
        $row = $row[0];
    }
    list($id, $title, $summary, $ctime, $wiki, $home, $svn, $status, $creator, $members, $ef1, $ef2) = $row;
    $title = htmlspecialchars_decode($title, ENT_QUOTES);
    $summary = htmlspecialchars_decode($summary, ENT_QUOTES);

    echo "<script type='text/javascript'>\n";
    echo "var _pType='$type';\n";
    echo "var _pId='$id';\n";
    echo "</script>\n";
?>
</head>

<body>
    <h2>添加或编辑项目信息</h2> 

    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="inputTitle">标题</label>
            <div class="controls">
<?php
                echo "<input id='inputTitle' placeholder='title' class='input-xxxlarge' value='$title'>\n";
?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputSummary">项目概述</label>
            <div class="controls">
<?php
                echo "<textarea id='inputSummary' placeholder='summary' class='input-xxxlarge'>$summary</textarea>\n";
?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputWiki">Wiki地址</label>
                <div class="controls">
<?php
                    echo "<input id='inputWiki' placeholder='wiki' class='input-xxxlarge' value='$wiki'>\n";
?>
                </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputHome">项目首页</label>
            <div class="controls">
<?php
                echo "<input id='inputHome' placeholder='home page' class='input-xxxlarge' value='$home'>\n";
?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputSvn">Svn地址</label>
            <div class="controls">
<?php
                echo "<input id='inputSvn' placeholder='svn url' class='input-xxxlarge' value='$svn'>\n";
?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputStatus">项目状态</label>
            <div class="controls">
            <select id="inputStatus">
<?php

    $option_list = Array(
        "项目提出",
        "需求草稿",
        "需求确定",
        "项目进行",
        "项目取消",
        "项目暂停",
        "项目完成",
    );

    for($i = 0; $i < count($option_list); $i++) {
        if ($i != $status) {
            echo "<option>" . $option_list[$i] . "</option>\n";
        } else {
            echo "<option selected>" . $option_list[$i] . "</option>\n";
        }
    }
?>
            </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputCreator">提出者</label>
            <div class="controls">
<?php
                echo "<input id='inputCreator' placeholder='creator' class='input-xxxlarge' value='$creator'>\n";
?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="inputMembers">项目成员</label>
            <div class="controls">
<?php
                echo "<input id='inputMembers' placeholder='members' class='input-xxxlarge' value='$members'>\n";
?>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <button id="submitBtn" class="btn">提交</button>
                <button id="cancelBtn" class="btn">取消</button>
            </div>
        </div>
    </form>
    
    <script src="../js/newOrEdit.php.js"></script>
</body>
</html>
