<html>
<head>
    <script src="../../../js-base/jquery.min.js" type="text/javascript"></script>
    <meta charset="utf-8">
    <style>
        table tr td input#title {width:793px};
    </style>
<?php
    if (!array_key_exists("user", $_GET)) {
        echo "错误：无效的用户名称！";
        return false;
    }
?>
</head>

<body>
<table style="width:100%">
    <tr>
        <th>标题:</th>
        <td><input type=text id="title"/></td>
    </tr>
    <tr>
        <th>正文:</th>
        <td><textarea id="content" cols=110 rows=10></textarea></td>
    </tr>
    <tr>
        <th>密码:</th>
        <td><input type=text id="passwd" placeholder="请记住该密码"/></td>
    </tr>
    <tr>
        <th></th>
        <td>
            <input type=button id="submit" value="保存" />
            <input type=button id="return" value="返回" />
        </td>
    </tr>
</table>

<script>
<?php
    echo "var author = \"" . $_GET["user"] . "\";\n";
?>
$(function() {
    $("input#title").focus();

    $("input#submit").click(function(e) {
        var title = $("input#title").val();
        var content = $("textarea#content").val();
        var passwd  = $("input#passwd").val();

        if (title == "" || content == "" || passwd == "") {
            alert("标题,内容,密码都不能为空");
            return false;
        }

        $.post("./postNewVote.php", {"title":title, "content":content, "author":author, "password":passwd}, function(data) {
            window.location.href="../index.php";
        });
    });

    $("input#return").click(function(e) {
            window.location.href="../index.php";
    });
});
</script>
</body>
</html>
