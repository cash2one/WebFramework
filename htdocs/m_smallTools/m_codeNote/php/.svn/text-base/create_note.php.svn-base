<?php

date_default_timezone_set("PRC");

$product = stripslashes($_POST["product"]);
$product = trim($product);

$title   = stripslashes($_POST["title"]);
$content = stripslashes($_POST["content"]);
$sponsor = stripslashes($_POST["sponsor"]);
$comment = stripslashes($_POST["comment"]);

$ret_obj = Array(
    "ret" => 0,
    "msg" => "保存成功",
);

# try at most 10 times
for($i = 0; $i < 10; $i ++) {
    $time_str = strftime("%Y-%m-%d %H:%M:%S", time());

    $file_main = strftime("%Y%m%d%H%M%S", time());
    $title_file = "../data/$product/$file_main.title";
    $desc_file = "../data/$product/$file_main.content";

    if (file_exists($title_file) || file_exists($desc_file)) {
        if ($i == 9) {
            $ret_obj["ret"] = 1;
            $ret_obj["msg"] = "错误:要创建的文件已存在，很邪乎，试了10次都一样";
            break;
        }
        sleep(1);
    } else {
        $ret = touch($title_file) && touch($desc_file);
        if ($ret == false) {
            $ret_obj["ret"] = 1;
            $ret_obj["msg"] = "错误:创建文件失败";
        } else {
            $title_line = implode("", Array($time_str, $title, $sponsor));
            file_put_contents($title_file, $title_line);

            file_put_contents($desc_file, $content);
        }
        break;
    }
}

echo json_encode($ret_obj);
