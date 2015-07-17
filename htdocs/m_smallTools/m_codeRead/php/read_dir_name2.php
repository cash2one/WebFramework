<?php

$cur_path = $_GET["cur_path"];

$retArray = Array();
$type = "dir";
$class_str = "brush:java";
$content = "";

if (is_file($cur_path )) {
    $type = "file";

    $filename = basename($cur_path); 
    // $type_str = array_pop($tempArray);

    if (strstr($filename, ".sh")) {
        $class_str = "brush:bash";

    }else if (strstr($filename, ".py")) {
        $class_str = "brush:python";

    }else if (strstr($filename, ".php")) {
        $class_str = "brush:php brush:xml brush js";

    }else if (strstr($filename, ".js")) {
        $class_str = "brush:js";

    }else if (strstr($filename, ".xml")) {
        $class_str = "brush:xml";

    }else if (strstr($filename, ".html")) {
        $class_str = "brush:xml brush:js";
    }

    $content = file_get_contents($cur_path);
    $content = str_replace("<", "&lt;", $content);
    $content = str_replace(">", "&gt;", $content);
    if (strlen($content) > 50000) {
        $content = substr($content, 0, 50000) . "\n[Warning]: file size too big, truncated it..."; //避免文件太大
    }
    
} else {
    $handle = opendir($cur_path);
    while (false !== ($file = readdir($handle))) {
        if (substr($file, 0, 1) == ".") continue; 
        array_push($retArray, $file); 
    }

    sort($retArray);
    array_unshift($retArray, "select...");
}

echo json_encode(
    Array(
        "data" => implode(":", $retArray),
        "type" => $type,
        "content" => $content,
        "class_str" => $class_str,
    )
);
