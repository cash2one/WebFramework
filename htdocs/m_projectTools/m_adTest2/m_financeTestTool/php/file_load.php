<?php

$conf_file = $_POST["filename"];
$file_content = file_get_contents($conf_file);
$file_content = str_replace("&", "&amp;", $file_content);
$file_content = str_replace(">", "&gt;", $file_content);
$file_content = str_replace("<", "&lt;", $file_content);
$file_content = str_replace("\n", "<br>", $file_content);
$file_content = str_replace(" ", "&nbsp;", $file_content);
echo $file_content;
