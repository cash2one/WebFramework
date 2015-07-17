<?php

date_default_timezone_set("PRC");

$svn      = stripslashes($_GET["svn"]);
$product  = stripslashes($_GET["prod"]);
$user     = stripslashes($_GET["user"]);
$password = stripslashes($_GET["password"]);

#$svn = "https://dev.corp.youdao.com/svn/outfox/products/ad/ead/tags/eadm-restrict-pre1@422377";
#$product  = "mail";
#$user     = "zhangpei";
#$password = "";

$svn = str_replace("/@", "@", $svn); //归一化svn url
$svn = trim($svn, "//");

$fields   = explode("/", $svn);
$root_dir = "../svn_src_code/$product/";
if (! is_dir($root_dir)) {
    mkdir($root_dir);
}

$name     = array_pop($fields);
$src_dir  = $root_dir . $name;

$ret_array = array(
    0, /* ret */
    null, /* data */
    $src_dir, /* root_dir */
);

function export_svn_code($user, $password, $svn, $src_dir) {
    global $ret_array;

    if (is_dir($src_dir)) {
        return;
    }

    $exp_file = "svn_export.exp";
    $file_content = file_get_contents("svn_export.template");
    $file_content = str_replace('$user_name$', $user, $file_content);
    $file_content = str_replace('$password$', $password, $file_content);
    $file_content = str_replace('$current_file$', $exp_file, $file_content);
    $file_content = str_replace('$url$', $svn, $file_content);
    $file_content = str_replace('$dir$', $src_dir, $file_content);
    file_put_contents($exp_file, $file_content);

    $cmd = "expect $exp_file"; 
    exec($cmd, $lines, $ret);

    $ret_array[1] = array_pop($lines);
}
export_svn_code($user, $password, $svn, $src_dir);

if (strstr($ret_array[1], "failed") != false || 
    strstr($ret_array[1], "doesn't exist") != false) {
    $ret_array[0] = 1;
    echo json_encode($ret_array);
    exit(1);
}

$cmd = "cd ../python; ./BeanFilesParser.py $src_dir $product";
exec($cmd, $lines, $ret);
if ($ret != 0) {
    $ret_array[0] = $ret;
    $ret_array[1] = "Error: 解析产品代码失败! 请确保类型和代码是匹配的";
    echo json_encode($ret_array);
    exit(1);
}

$time_str = strftime("%Y-%m-%d %H:%M:%S", time());
$line = "<tr><td>$time_str</td><td>$user</td><td>$svn</td><td class='prod'>$product</td></tr>\n";
file_put_contents("../logs/log.txt", $line, FILE_APPEND);

$ret_array[0] = $ret;
$ret_array[1] = json_decode($lines[0]);
echo json_encode($ret_array);

?>
