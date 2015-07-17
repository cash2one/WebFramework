<?php
    header("Content-type: text/html; charset=utf-8");
    include("../util/htmlUtil.php");
?>


<center>
<form method="POST" action="./php/pages/results/codeSearchResultPage.php">
<table>
    <tr>
        <td colspan='2'><input type="text" id="search_box" name="search_str" size=60 placeholder="关键字" /></td>
        <td colspan='2'><input type="submit" id="search_btn" value="搜索" /></td>
    </tr>  
    <tr>
        <td>产品名:<select id="prod_name" name="prod_name"><?php html_listProducts(); ?></select></td><td></td>
    </tr>
    <tr>
        <td>版本号:<select id="version_id" name="version_id"><?php $prodList = dbUtil(GetProdList); html_listVersions($prodList[0], true); ?></select></td><td></td>
    </tr>
</table>
</form>
</center>

<script>
$(function(e) {
    $("input#search_box").focus();

    $("select#prod_name").change(function(e) {
        var prodName = $(this).val();
        $("select#version_id").html("").load("./php/tools/get_version_list.php", {"prodName": prodName, "showAll": 1});
    });
});
</script>
