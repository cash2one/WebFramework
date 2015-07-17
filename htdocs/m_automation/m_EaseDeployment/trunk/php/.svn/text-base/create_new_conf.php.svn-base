<?php

require_once("settings.php");

$user = $_GET["user"];
$filename = trim($_GET["filename"]);
$dir = $conf_dir . "$user/";

if ($handle = opendir($dir)) {

   	while (false !== ($file = readdir($handle))) {
		if ($file[0] == ".") continue;

		if ($file == $filename) {
			echo "文件名($filename) 已存在!";
			return;
		}
   	}

   	closedir($handle);
	
	touch($dir . "/" . $filename);
	echo "新建文件('$filename') 成功";
}
