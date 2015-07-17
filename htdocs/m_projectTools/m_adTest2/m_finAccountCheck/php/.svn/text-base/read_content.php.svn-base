<?php
   
$file = $_GET["html_file"];
$filename = basename($file);

$lines = Array();
$title = substr($filename, 0, 4) . "年" . substr($filename, 4, 2) . "月的对账结果";

$content  = "<h4>================ $title =================</h4>\n";
$content .= "<table border='1'>\n";
$content .= file_get_contents($file) . "\n";
$content .= "</table>\n";
$content .= "<br>\n";

echo $content;
