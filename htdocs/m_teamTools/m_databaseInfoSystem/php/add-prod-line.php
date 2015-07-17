<?php

$prod_line_name = $_GET["prod_line_name"];

$ret_arr = Array(
    "0",
    "产品线被成功创建",
);

$dir = "../data/$prod_line_name";
if (is_dir($dir)) {
    $ret_arr = Array(
        "1",
        "错误:该产品线已存在!",
    );
} else {
    $ret = mkdir($dir);
    if (! $ret) {
        $ret_arr = Array(
            "1",
            "错误:创建该产品线失败!",
        );
    }
}

echo json_encode($ret_arr);
