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
        echo "Filename($newfilename) Already Exists";
	else{
		rename($old_file_path, $new_file_path);
	    echo "Rename file($oldfilename) as ($newfilename) Successfully";
	}
    return;
}
echo "Rename ('$oldfilename') Failed";

