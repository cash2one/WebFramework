<?php

define("LOG_FILE", "./svn.history");

$src_code = trim($_GET["src_addr"]);
# $src_code = "http://apache.fayea.com/apache-mirror//jmeter/source/apache-jmeter-2.9_src.zip";

function save_log($src_code) {
    $line = "Anoymous" . $src_code;
    file_put_contents(LOG_FILE, "$line\n", FILE_APPEND);
}

function check_file_type($filename, $type_name) {
    $len = strlen($type_name);
    if (substr($filename, -$len) == $type_name) return true;
    return false;
}

// download src code
$md5        = md5($src_code);
$target_dir = "../code_dir/$md5";
$cmd        = "rm -rf $target_dir; mkdir $target_dir; cd $target_dir; wget '$src_code'";
system($cmd);

// depress the compressed file
$file = array_pop(glob("$target_dir/*"));
$filename = basename($file);

chdir($target_dir);
if (check_file_type($filename, "zip")) {
    $cmd = "unzip $filename";
} else if (check_file_type($filename, "tgz")) {
    $cmd = "tar -zxvf $filename";
} else {
    echo "Invalid file type: $filename";
    exit(1);
}
system($cmd . " > /dev/null");

chdir("../../php");
save_log($src_code);

echo "file downloaded successfully";
