<?php
    header("Content-type: text/html; charset=utf-8");
    include("../util/htmlUtil.php");
?>

<head>
    <style>
        a {text-decoration: none; color: blue}
        a:hover {text-decoration: underline;}
    </style>
</head>

<center>
<form method="POST" action="./php/comment.php">
<table>
    <tr>
        <td>产品名:<select id="prod_name" name="prod"><?php html_listProducts(); ?></select></td>
        <td><input type="submit" id="comment_btn" value="添加备注" /></td>
    </tr>  
    <tr>
        <td>版本号:<select id="version_name" name="ver"><?php $prodList = dbUtil(GetProdList); html_listVersions($prodList[0], 0); ?></select></td>
    </tr>
    <tr>
        <td>备注人:<select id="commentor_name" name="commentor"><?php html_listCommentors($prodList[0]); ?></select> <a href='' id="add_commentor">+</a></td>
    </tr>
</table>
</form>
</center>

<div id="code_info"></div>

<script>
$(function(e) {
    get_code_info();

    $("td a#add_commentor").click(function(e) {
        var ret = prompt("Please name the creator:");
        if (ret == null) return false;
        $("select#commentor_name").append("<option selected>" + ret + "</option>");

        e.preventDefault();
    });

    $("select#prod_name").change(function(e) {
        var prodName = $(this).val();
        $("select#version_name").html("").load("./php/tools/get_version_list.php", {"prodName": prodName}, function(data) {
            get_code_info();
        });

        $("select#commentor_name").html("").load("./php/tools/get_commentor_list.php", {"prodName": prodName});
    });

    $("select#version_name").change(function(e) {
        get_code_info();
    });

    function get_code_info() {
        var prodName = $("select#prod_name").val();
        var version  = $("select#version_name").val();
        $("div#code_info").html("").load("./php/tools/get_code_info.php", {"prodName": prodName, "version": version});
    }
});
</script>
