<?php

    include_once("./settings.php");
	$ret_list = array();
    $temp_dir_name = "temp";

	if ($handle = opendir($conf_dir)) {

    	while (false !== ($dir = readdir($handle))) {
			if ($dir[0] == "." || $dir == $temp_dir_name) continue;
            if (is_file($conf_dir . "/" . $dir)) continue;

			array_push($ret_list, $dir);
    	}

    	closedir($handle);
	}

	echo implode("", $ret_list);
