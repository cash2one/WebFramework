<?php

$dir_name = $_GET["dir_name"];
$retArray = Array();

$handle = opendir("../code_dir/" . $dir_name);
while (false !== ($file = readdir($handle))) {
    if (substr($file, 0, 1) == ".") continue; 
    array_push($retArray, $file); 
}

sort($retArray);
array_unshift($retArray, "select...");
echo implode(":", $retArray);
