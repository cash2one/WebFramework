<?php

header("Content-type:text/html; charset:utf-8");
include(dirname(__FILE__) . "/../php/dbLib.php");
include(dirname(__FILE__) . "/../php/commonLib.php");

$svnUrl   = trim($_POST["svnUrl"]);
$prodName = trim($_POST["prodName"]);
$ldap     = $_POST["ldap"];
$passwd   = $_POST["passwd"];

$input_file  = "templates/svn_export.template";
$expect_script_file = "templates/svn_export.exp";
$target_dir = dirname(__FILE__) . "/../code/" . md5($prodName);

$tableArr = dbUtil(LoadCodePathInfoTable);
foreach ($tableArr as $arr) {
    if ($arr["svnPath"] == $svnUrl) {
        echo "错误：该svn地址已经存在于表CodePathInfo中(对应的产品名: " . $arr["prodName"] . ")!";
        return;
    }   
}       

echo "<pre>", "\n";
if (strlen($prodName) > 30) {
    echo "错误：服务名称不能超过30个字符！"; 
    return; 
}

if (preg_match("/^(https:\/\/[^@]+)@(\d+)$/", $svnUrl, $matches) == 0) { 
    echo "错误：无效的svn地址!";
    return; 
}
$version  = $matches[2];

if (!file_exists($target_dir)) {
    mkdir($target_dir);
}
$target_dir = realpath($target_dir);

$target_dir = $target_dir . "/" . $version;
if (file_exists($target_dir)) {
    echo "错误：该版本已经存在!";
    return; 
}

$temp_arr = array(
    '$current_file$'   => $expect_script_file,
    '$user_name$'      => $ldap,
    '$svn_url$'        => $svnUrl,
    '$target_dirname$' => $target_dir,
    '$password$'       => $passwd,
);
$ret = update_file($input_file, $expect_script_file, $temp_arr);
if ($ret == false) {
    $retArr = get_lib_result();
    echo $retArr["msg"];
    return;
}

$run_cmd = "chmod 700 $expect_script_file; $expect_script_file";
exec($run_cmd, $lines, $ret);
$log_content = implode("\n", $lines);
echo $log_content, "\n";
if (strpos($log_content, "authorization failed:") !== false || strpos($log_content, "doesn't exist") !== false) {
    return;
}

echo "\n\n", "=================================================================================================================", "\n\n";

$ret = dbUtil(SaveCodePathInfo, $svnUrl, $version, $prodName, $ldap);
$retArr = dbUtil(GetStatus);
echo $retArr["msg"], "\n";
if ($retArr["ret"] == 1) {
    system("rm -rf $target_dir");
    echo "迁出目录($target_dir)已经删除";
}
