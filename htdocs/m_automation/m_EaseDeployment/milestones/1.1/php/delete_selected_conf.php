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
			echo "Delete file('$file_path') Successfully";
			return;
		}
   	}

   	closedir($handle);
	echo "Delete ('$file_path') Failed";
}
