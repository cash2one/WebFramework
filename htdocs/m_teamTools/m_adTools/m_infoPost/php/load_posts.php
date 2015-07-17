<?php

mb_internal_encoding("UTF-8");
$dir = opendir("../posts");
$list = array();
//列出 images 目录中的文件
while (($file = readdir($dir)) !== false)
{
    if (substr($file, 0, 1) == ".") continue;

    $file_pre_str = mb_substr(file_get_contents("../posts/$file"), 0, 80);
    $time = substr($file, 0, 4) . "年" . substr($file, 4, 2) . "月" . substr($file, 6, 2) . "日 " . substr($file, 8, 2) . "时" . substr($file, 10, 2) . "分" . substr($file, 12, 2) . "秒";
    array_push($list, "<tr><td id='$file'><div class='title'>" . $file_pre_str. "</div><div class='time'>" . "$time" . "</div></td></tr>");
}
closedir($dir);

echo "<table>\n";
sort($list);
$line_cnt = count($list);
foreach (array_reverse($list) as $td) {
    echo str_replace("'title'>", "'title'>m_$line_cnt. ", $td);
    $line_cnt -= 1;
}
echo "</table>\n";
