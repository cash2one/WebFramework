<?php
    header("Content-type: text/html; charset=utf-8");
    include("../util/htmlUtil.php");
?>

<head>
    <style>
        td a {text-decoration: none; color: blue}
        td a:hover {text-decoration: underline;}
    </style>
</head>

<center>
<form method="POST" action="./php/pages/results/svnAddResultPage.php">
<table>
    <tr>
        <td colspan='2'><input type="text" id="add_box" name="code_str" size=120 placeholder="svn地址@版本" /></td>
        <td colspan='2'><input type="submit" id="add_btn" value="添加" /></td>
    </tr>  
    <tr>
        <td>产品名:<select id="prod_name" name="prod_name"><?php html_listProducts(); ?><select/> <a title="添加产品名称" href='' id="add_prodName">+</a></td><td></td>
    </tr>
    <tr>
        <td>Ldap:<input type="text" id="ldap" name="ldap" /> Password:<input type="password" id="password" name="passwd" /></td><td></td>
    </tr>
</table>
</form>

</center>

<script>
$(function(e) {
    $("input#add_box").focus();

    $("td a#add_prodName").click(function(e) {
        var ret = prompt("Please name the product:");
        if (ret == null) return false;
        $("select#prod_name").append("<option selected>" + ret + "</option>");

        e.preventDefault();
    });
});
</script>
