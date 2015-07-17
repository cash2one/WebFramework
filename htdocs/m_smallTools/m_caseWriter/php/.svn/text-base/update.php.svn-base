<?php
date_default_timezone_set("PRC");

$id = stripslashes($_GET["id"]);
$title = stripslashes($_GET["title"]);
$service_name = stripslashes($_GET["service_name"]);
$user = stripslashes($_GET["user"]);
$ticket_addr = stripslashes($_GET["ticket_addr"]);
$comment = stripslashes($_GET["comment"]);

$ret_obj = Array(
    "ret" => 0,
    "message" => "保存成功",
);

$old_file_path  = "../case_list_dir/" . $id . ".title";
$old_file_path2 = "../case_list_dir/" . $id . ".content";
$new_id = md5($title);
$file_path  = "../case_list_dir/" . $new_id . ".title";
$file_path2 = "../case_list_dir/" . $new_id . ".content";

if (file_exists($file_path) && $old_file_path != $file_path) {
    $ret_obj["ret"] = 1;
    $ret_obj["message"] = "错误: 用例集合 $title 已存在";

} else {
    // 获取当前时间串
    if (file_exists($old_file_path)) {
        $old_file_content = file_get_contents($old_file_path);
        $fields = explode("", $old_file_content);
        $time_str = $fields[0];
    } else {
        $time_str = strftime("%Y-%m-%d %H:%M:%S", time());
    }
    file_put_contents($old_file_path, implode("", Array($time_str, $title, $service_name, $user, $ticket_addr, $comment)));

    if ($old_file_path != $file_path) {
        rename($old_file_path, $file_path);
        rename($old_file_path2, $file_path2);
    }
}

echo json_encode($ret_obj);

?>
