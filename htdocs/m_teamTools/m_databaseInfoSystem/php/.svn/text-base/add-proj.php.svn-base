<?php

$prod_line_name = $_GET["prod_line_name"];
$proj_name      = $_GET["proj_name"];

$ret_arr = Array(
    "0",
    "项目或服务被成功创建",
);

$dir = "../data/$prod_line_name/$proj_name";
if (is_dir($dir)) {
    $ret_arr = Array(
        "1",
        "错误:该项目/服务已存在!",
    );
} else {
    $ret = mkdir($dir);
    if (! $ret) {
        $ret_arr = Array(
            "1",
            "错误:创建该项目/服务失败!",
        );
    }
}

echo json_encode($ret_arr);
