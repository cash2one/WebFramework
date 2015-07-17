<?php

require_once("settings.php");

$to_user = $_GET["touser"];
$from_user = $_GET["fromuser"];
$from_filename = $_GET["oldfilename"];
$to_filename = $_GET["newfilename"];
$dir = $conf_dir . "$from_user/";
$to_file_path = "$conf_dir$to_user/$to_filename";
$from_file_path = "$conf_dir$from_user/$from_filename"; 

if (file_exists($from_file_path)){
	if (file_exists($to_file_path)){
		echo "文件名 ($to_filename) 已存在!";
		return;
	}else{
		copy($from_file_path, $to_file_path);
		echo "复制文件($from_filename) 到 ($to_filename) 成功";
        return;
	}
}
echo "复制文件 ('$from_filename') 失败!";

