<?php

    include_once("./settings.php");

	$user = $_GET["user"];
	$dir = $conf_dir . "$user";

	$ret_list = array();

	if ($handle = opendir($dir)) {

    	while (false !== ($file = readdir($handle))) {
			if ($file[0] == ".") continue;
            if (is_dir($dir . "/" . $file)) continue;

			array_push($ret_list, $file);
    	}

    	closedir($handle);
	}

	echo implode("", $ret_list);
