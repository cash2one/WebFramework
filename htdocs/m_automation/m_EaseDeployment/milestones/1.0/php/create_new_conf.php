<?php

require_once("settings.php");

$user = $_GET["user"];
$filename = trim($_GET["filename"]);
$dir = $conf_dir . "$user/";

if ($handle = opendir($dir)) {

   	while (false !== ($file = readdir($handle))) {
		if ($file[0] == ".") continue;

		if ($file == $filename) {
			echo "Filename($filename) Already Exists";
			return;
		}
   	}

   	closedir($handle);
	
	touch($dir . "/" . $filename);
	echo "Create file('$filename') Successfully";
}
