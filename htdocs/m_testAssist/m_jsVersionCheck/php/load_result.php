<?php

date_default_timezone_set('Asia/Shanghai'); 
define("TEMPLATE_FILE_EXPORT", "../conf/svn_export.template");
define("TEMPLATE_FILE_DIFF", "../conf/svn_diff.template");
define("LOG_FILE", "../log/log.txt");
define("DATA_DIR", "../svn_data/");

$ldap    = trim($_POST["ldap"]);
$passwd  = $_POST["passwd"];
$code_v1 = trim($_POST["code1"]);
$code_v2 = trim($_POST["code2"]);

### dollar sign is key word for expect script
$passwd = str_replace('$', '\\$', $passwd);

// In case someone like to use https://xxx:34343
$code_v1 = preg_replace("/:(\d+)$/", "@\\1", $code_v1);
$code_v2 = preg_replace("/:(\d+)$/", "@\\1", $code_v2);
$code_v1_md5 = md5($code_v1);
$code_v2_md5 = md5($code_v2);
$svn_dir1 = DATA_DIR . $code_v1_md5;
$svn_dir2 = DATA_DIR . $code_v2_md5;
$svn_diff_file = DATA_DIR . $code_v1_md5 . "-" . $code_v2_md5 . ".diff";

// save the log
save_log($ldap, $code_v1, $code_v2);

$svn_v1_at_pos = strpos($code_v1, "@");
$svn_v2_at_pos = strpos($code_v2, "@");
$need_diff = false;

if (! file_exists($svn_dir1) || $svn_v1_at_pos == false) {
    svn_export($ldap, $passwd, $code_v1, $svn_dir1);
    $need_diff = true;
}
if (! file_exists($svn_dir2) || $svn_v2_at_pos == false) {
    svn_export($ldap, $passwd, $code_v2, $svn_dir2);
    $need_diff = true;
}
if (! file_exists($svn_diff_file) || $need_diff == true) {
    svn_diff($ldap, $passwd, $code_v1, $code_v2, $svn_diff_file);
}

# call bash file to generate result.php file
system("sh ./check_js.sh $svn_dir1 $svn_dir2 $svn_diff_file");
# touch("./result.php");

# output result to front
include("./result.php");

# will delete it later
/*
$old_array = Array(
    "js_file_path1" => Array(
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
    ),
    "js_file_path2" => Array(
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
    ),
);

$new_array = Array(
    "js_file_path1" => Array(
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
    ),
    "js_file_path2" => Array(
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
        Array("2323", "dfasa", "adsfa"),
    ),
);
*/

$ret_lines = Array(
    "<table border='1'>",
    "<tr><th></th><th colspan='3'>老的SVN</th><th colspan='3'>新的SVN</th></tr>",
    "<tr><th>被引用的js文件</th><th>引用js的文件</th><th>行号</th><th>引用的版本</th><th>引用的文件</th><th>行号</th><th>引用的版本</th></tr>"
);

# $old_aray vs $new_array
foreach ($new_array as $js_file_path => $temp_array1) {
    if (array_key_exists($js_file_path, $old_array)) {
        $temp_array2 = $old_array[$js_file_path];
    } else {
        $temp_array2 = Array();
    }

    $count_delta = count($temp_array1) - count($temp_array2);
    for ($i = 0; $i < $count_delta; $i ++) {
        if ($count_delta > 0) {
            array_push($temp_array2, Array("", "", ""));
        } else {
            array_push($temp_array1, Array("", "", ""));
        }
    }

    $count_all = count($temp_array1);
    for ($i = 0; $i < $count_all; $i++) {
        if ($i == 0) {
            array_push($ret_lines, "<tr><td rowspan='$count_all'>$js_file_path</td><td>" . implode("</td><td>", $temp_array1[$i]) . "</td><td>" . implode("</td><td>", $temp_array2[$i]) . "</td></tr>");
        } else {
            array_push($ret_lines, "<tr><td>" . implode("</td><td>", $temp_array1[$i]) . "</td><td>" . implode("</td><td>", $temp_array2[$i]) . "</td></tr>");
        }
    }
}
array_push($ret_lines, "</table>");

echo implode("\n", $ret_lines);

// save user query log
function save_log($ldap, $code_v1, $code_v2) {
    $line = "<tr><td>" . strftime("%Y-%m-%d %H:%M:%S", time()) . "</td><td>" . $ldap . "</td><td>" . $code_v1 . "</td><td>" . $code_v2 . '</td></tr>';
    file_put_contents(LOG_FILE, "$line\n", FILE_APPEND);
}

// build expect script from template by filling some info
function svn_export($ldap, $passwd, $svn_addr, $svn_local_dir) {
    $output_file = "./svn_export.exp";

    $content = file_get_contents(TEMPLATE_FILE_EXPORT);
    $content = str_replace('$current_file$', $output_file, $content);
    $content = str_replace('$svn_addr$', $svn_addr, $content);
    $content = str_replace('$svn_local_dir$', $svn_local_dir, $content);
    $content = str_replace('$user_name$', $ldap, $content);
    $content = str_replace('$password$', $passwd, $content);
    file_put_contents($output_file, $content);

    $cmd = "chmod 700 $output_file; $output_file";
    exec($cmd, $lines, $ret);
    if ($ret != 0) {
        echo "Error: Failed when exec expect script!";
        exit(1);
    }
}

function svn_diff($ldap, $passwd, $code_v1, $code_v2, $diff_file) {
    $output_file = "./svn_diff.exp";
    
    $content = file_get_contents(TEMPLATE_FILE_DIFF);
    $content = str_replace('$current_file$', $output_file, $content);
    $content = str_replace('$svn_old$', $code_v1, $content);
    $content = str_replace('$svn_new$', $code_v2, $content);
    $content = str_replace('$user_name$', $ldap, $content);
    $content = str_replace('$password$', $passwd, $content);

    file_put_contents($output_file, $content);

    $cmd = "chmod 700 $output_file; $output_file > $diff_file 2>/dev/null";
    exec($cmd, $lines, $ret);
    if ($ret != 0) {
        echo "Error: Failed when exec expect script!";
        exit(1);
    }
}
