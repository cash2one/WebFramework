<?php

require_once("settings.php");

$user = $_GET["user"];
$filename = $_GET["filename"];
$dir = $conf_dir . "$user";
$file_path = "$dir/$filename";

if ($handle = opendir($dir)) {

   	while (false !== ($file = readdir($handle))) {
		if ($file == $filename) {
			unlink($file_path);
			echo "删除文件('$file_path') 成功";
			return;
		}
   	}

   	closedir($handle);
	echo "删除文件 ('$file_path') 失败!";
}
