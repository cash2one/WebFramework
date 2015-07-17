<?php

date_default_timezone_set("PRC");

include("./sqlite_lib.php");

$raw_name = $_POST["raw_name"];
$target_name = $_POST["target_name"];
$user_name = $_POST["user"];

$filesize = getRealSize("../books/$target_name");

$ctime = date("Y-m-d H:i:s", time());
save_data(Array($target_name, $raw_name, $user_name, $ctime, "", 0, $filesize, "", ""));

function getRealSize($file) { 
    $size = filesize($file);
    $kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte
    
    if($size < $kb)
    { 
        return $size." B";
    }
    else if($size < $mb)
    { 
        return round($size/$kb,2)." KB";
    }
    else if($size < $gb)
    { 
        return round($size/$mb,2)." MB";
    }
    else if($size < $tb)
    { 
        return round($size/$gb,2)." GB";
    }
    else
    { 
        return round($size/$tb,2)." TB";
    }
}
