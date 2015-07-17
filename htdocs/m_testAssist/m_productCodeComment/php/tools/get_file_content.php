<?php

include(dirname(__FILE__) . "/../commonLib.php");

$filePath = $_GET["file_path"];
// $filePath = "/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_testAssist/m_productCodeComment/code/bf3c7cfd35b54b58eb4fa83eca5a5f35/499969/build.xml";
// $filePath = "/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_testAssist/m_productCodeComment/code/bf3c7cfd35b54b58eb4fa83eca5a5f35/499969/src/java/outfox/ead/stat/mongo/model/Order.java";
$retArray = getFileContent($filePath, "red");
$type  = $retArray["type"];
$lines = $retArray["lines"];

$content = "";

if ($type == "image" || $type == "error") {
    $content = $lines[0];

} elseif ($type == "text") {
    $line_cnt = count($lines);
    $temp_arr = array();
    $line_idx_classname = "idx_span";
    $line_classname = "cont_span";

    $line_idx = 1;
    foreach ($lines as $arr) {
        list($line, $funcName) = $arr;
        if ($line_cnt < 10) {
            $line = sprintf("<span class='$line_idx_classname'>%s</span><span class='$line_classname'>%s</span>", $line_idx, $line);

        } elseif ($line_cnt < 100) {
            $line = sprintf("<span class='$line_idx_classname'>%2s</span><span class='$line_classname'>%s</span>", $line_idx, $line);

        } elseif ($line_cnt < 1000) {
            $line = sprintf("<span class='$line_idx_classname'>%2s</span><span class='$line_classname'>%s</span>", $line_idx, $line);

        } elseif ($line_cnt < 10000) {
            $line = sprintf("<span class='$line_idx_classname'>%2s</span><span class='$line_classname'>%s</span>", $line_idx, $line);
        }
        $line_idx ++;
        array_push($temp_arr, "<div name='func_$funcName' class='file_line'>" . $line . "</div>");
    }

    $content = implode("", $temp_arr);
} 

echo json_encode(
    array(
        "content" => $content,
        "funcNames" => array_count_values($retArray["funcNames"]))
);
