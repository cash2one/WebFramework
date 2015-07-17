<?php

define("TEMPLATE_FILE", "../conf/svn_export.template");
define("LOG_FILE", "./svn.history");

$ldap    = $_POST["ldap"];
$passwd  = $_POST["passwd"];
$code_v1 = $_POST["code"];

### dollar sign is key word for expect script
$passwd = str_replace('$', '\\$', $passwd);

// In case someone like to use https://xxx:34343
$svn_code = preg_replace("/:(\d+)$/", "@\\1", $code_v1);
$svn_code = trim($svn_code);

// save user query log
function save_log($ldap, $code_v1) {
    $line = $ldap. "" . $code_v1;
    file_put_contents(LOG_FILE, "$line\n", FILE_APPEND);
}

// build expect script from template by filling some info
function svn_export_code($ldap, $passwd, $code_v1) {
    $output_file = "./svn_export.exp";
    $target_dir = "../code_dir/" . md5($code_v1);

    if (is_dir($target_dir)) {
        system("rm -rf $target_dir");
    }
    
    $content = file_get_contents(TEMPLATE_FILE);
    $content = str_replace('$current_file$', $output_file, $content);
    $content = str_replace('$svn_code$', $code_v1, $content);
    $content = str_replace('$user_name$', $ldap, $content);
    $content = str_replace('$password$', $passwd, $content);
    $content = str_replace('$target_dir$', $target_dir, $content);

    file_put_contents($output_file, $content);

    $cmd = "chmod 700 $output_file; $output_file";
    exec($cmd, $lines, $ret);
    if ($ret != 0) {
        echo "Error: Failed when exec expect script!";
        exit(1);
    }

    save_log($ldap, $code_v1);
    echo "svn export code successfully!";
}

svn_export_code($ldap, $passwd, $svn_code);
