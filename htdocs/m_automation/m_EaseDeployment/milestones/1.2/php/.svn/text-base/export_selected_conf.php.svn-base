<?php

require_once("settings.php");

$user = $_GET["user"];
$ldap = $_GET["ldap"];
$key = $_GET["key"];
$filename = $_GET["filename"];
$export_path = $_GET["path"];
$file_path = "$conf_dir$user/$filename";

function get_abs_path($path){
    $currentdir = dirname(__FILE__);
    $dirs = split('/',$path);
    foreach ($dirs as $dir){
        if ($dir == ".."){
            $currentdir = dirname($currentdir);
        }
        else
            $currentdir = "$currentdir/$dir";
    }
    return $currentdir;
}
if (file_exists($file_path)){
    $file_path = get_abs_path($file_path);
    $cmd = "cd /global/share/test/deploy_web;./su_user.expect $ldap '$key' ' ' \"scp -r $file_path $export_path\"";
    system($cmd,$returncode);
    return;
}

