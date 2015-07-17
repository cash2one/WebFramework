<?php

$req_str = stripslashes($_GET["req_str"]);
#$req_str = '{"anti":[],"click":["nb011:/disk4/eadop/click-resin/logs/log:0","nb015:/disk4/eadop/click-resin/logs/log:0","nc092:/disk4/eadop/click-resin/logs/log:0","nc072:/disk4/eadop/click-resin/logs/log:0"],"filter_str":"imprIp=61.","username":"zhangpei2","password":"df", "rsa_password":"abcdefg"}';
$reqObj = json_decode($req_str, true);

# extract user and password
$username = trim($reqObj["username"]);
$password = trim($reqObj["password"]);
$rsa_password = trim($reqObj["rsa_password"]);

# rebuild query str
unset($reqObj["username"]);
unset($reqObj["password"]);
unset($reqObj["rsa_password"]);
$query_str = json_encode($reqObj);

# build expect script
$cmd = "cd ../python; ./log_reader.py '$query_str'";
#### fixme: [ 对expect来说是特殊字符
$cmd = str_replace("[", "\\[", $cmd);
$cmd = str_replace('"', "\\\"", $cmd);
$file = build_query_log_exp_file($username, $password, $cmd, $rsa_password);

# query python script for result
$cmd2 = "./$file";
exec($cmd2, $lines, $ret);

$index = count($lines) - 1;
### file_put_contents("./abc.txt", implode("\n", $lines), FILE_APPEND);
$ret_line = $lines[$index];

if (substr($ret_line, 0, 3) == "su:") {
    echo json_encode(Array(
            "status" => "1",
            "message" => $ret_line,
         ));

} else if (strstr($ret_line, "Enter") != False) {
    echo json_encode(Array(
            "status" => "1",
            "message" => $ret_line,
         ));
} else {
    echo $ret_line;
}
    
function build_query_log_exp_file($username, $password, $cmd, $rsa_password) {
    $hash_str = md5($username . $password . $cmd . $rsa_password);
    $temp_file = "query_log.exp.template";
    //if ($username == "luqy") {
    //   $temp_file = "query_log_for_luqy.exp.template";
    // }
    $file = "query_log_$hash_str.exp";

    $file_content = file_get_contents($temp_file);
    $file_content = str_replace('$current_file$', $file, $file_content);
    $file_content = str_replace('$username$', $username, $file_content);
    $file_content = str_replace('$rsa_password$', $rsa_password, $file_content);
    $file_content = str_replace('$password$', $password, $file_content);
    $file_content = str_replace('$cmd$', $cmd, $file_content);
    file_put_contents($file, $file_content);

    system("chmod +x $file");

    return $file;
}
