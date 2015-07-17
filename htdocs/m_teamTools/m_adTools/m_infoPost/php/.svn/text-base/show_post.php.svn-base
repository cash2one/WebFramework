<?php

$file = stripslashes($_POST["file"]);
#$file = "20130301172656";
$content = file_get_contents("../posts/$file");
$content = str_replace("<", "&lt;", $content);
$content = str_replace(">", "&gt;", $content);
$content = str_replace("\n", "<br/>", $content);
echo "$content";
