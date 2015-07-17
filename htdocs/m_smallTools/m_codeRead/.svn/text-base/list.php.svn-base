<?php

$data_file = "./php/svn.history";
$dir_path = "./code_dir";
$lines = file($data_file);
foreach (array_unique($lines) as $line) {
    list($name, $url) = explode("", trim($line));
    echo "$dir_path/", md5($url), " => ", $url, "\n";
}
