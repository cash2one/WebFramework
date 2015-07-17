<?php
date_default_timezone_set("PRC");

$title = stripslashes($_GET["title"]);
$service_name = stripslashes($_GET["service_name"]);
$user = stripslashes($_GET["user"]);
$ticket_addr = stripslashes($_GET["ticket_addr"]);
$comment = stripslashes($_GET["comment"]);
$time_str = strftime("%Y-%m-%d %H:%M:%S", time());

$ret_obj = Array(
    "ret" => 0,
    "message" => "保存成功",
);

$file_path = "../case_list_dir/" . md5($title) . ".title";
if (file_exists($file_path)) {
    $ret_obj["ret"] = 1;
    $ret_obj["message"] = "错误: 用例集合 $title 已存在";
} else {
    file_put_contents($file_path, implode("", Array($time_str, $title, $service_name, $user, $ticket_addr, $comment)));
}

echo json_encode($ret_obj);

?>
