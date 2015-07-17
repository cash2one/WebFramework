<style>
    a {text-decoration: none}
    a:hover {text-decoration: underline}    
</style>

<?php
header("Content-type:text/html; charset=utf-8");

$code_url = $_POST["code_str"];
$prodname = $_POST["prod_name"];
$ldap     = $_POST["ldap"];
$passwd   = $_POST["passwd"];

include("../../util/util.php");
export_svn_code($code_url, $prodname, $ldap, $passwd);
$retArray = get_util_result();
$color = "green";
if ($retArray[0] == 1)
    $color = "red";
    
echo "<font color='$color'>" . $retArray[1] . "</font>", " <a href='../../../index.php' style='color:blue'>返回</a>";
