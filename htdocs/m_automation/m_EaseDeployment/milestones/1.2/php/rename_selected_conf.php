<?php

require_once("settings.php");

$user = $_GET["user"];
$newfilename = trim($_GET["newfilename"]);
$oldfilename = $_GET["oldfilename"];
$dir = $conf_dir . "$user";
$new_file_path = "$dir/$newfilename";
$old_file_path = "$dir/$oldfilename";

if (file_exists($old_file_path)){
	if (file_exists($new_file_path))
        echo "文件名($newfilename) 已存在!";
	else{
		rename($old_file_path, $new_file_path);
	    echo "重命名文件($oldfilename) 为 ($newfilename) 成功";
	}
    return;
}
echo "重命名 ($oldfilename) 失败!";

