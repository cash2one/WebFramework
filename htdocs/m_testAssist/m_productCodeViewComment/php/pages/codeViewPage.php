<?php
    header("Content-type: text/html; charset=utf-8");
    include("../util/htmlUtil.php");
?>

<center>
<form method="POST" action="./php/view.php">
<table>
    <tr>
        <td>产品名:<select id="prod_name" name="prod"><?php html_listProducts(); ?></select></td>
        <td><input type="submit" id="view_btn" value="查看代码" /></td>
    </tr>  
    <tr>
        <td>版本号:<select id="version_name" name="ver_name"><?php $prodList = dbUtil(GetProdList);  html_listVersions($prodList[0]); ?></select></td>
    </tr>
</table>
</form>
</center>

<div id="code_info"></div>

<script>
$(function(e) {
    get_code_info();

    $("select#prod_name").change(function(e) {
        var prodName = $(this).val();
        $("select#version_name").html("").load("./php/tools/get_version_list.php", {"prodName": prodName, "showAll": 0}, function(data) {
            get_code_info();
        });
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
