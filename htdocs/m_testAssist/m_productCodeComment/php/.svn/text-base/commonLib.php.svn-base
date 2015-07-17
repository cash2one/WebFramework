<?php

// === 全局变量定义区
$status_arr = array(
    "ret" => 0, 
    "msg" => ""
);

// == 状态函数定义区
function reset_lib_result() {
    global $status_arr;
    $status_arr = array(
        "ret" => 0, 
        "msg" => ""
    );
}

function set_lib_result($status, $msg) {
    global $status_arr;
    $status_arr = array(
        "ret" => $status, 
        "msg" => $msg
    );
    return true;
}

function get_lib_result() {
    global $status_arr;
    return $status_arr;
}

// == 内部函数定义区
function loadDirTree($dir, &$htmlLines) {
    foreach (glob($dir . "/*") as $path) {
        if (is_dir($path)) {
            array_push($htmlLines, "<li data-options='attributes:{file_path:\"\"}, state:\"closed\"'>");
            array_push($htmlLines, '<span>' . basename($path) . '</span>');
            array_push($htmlLines, '<ul>');
            loadDirTree($path, $htmlLines);
            array_push($htmlLines, '</ul>');
            array_push($htmlLines, '</li>');
        } else {
            array_push($htmlLines, "<li data-options='attributes:{file_path:\"$path\"}'>");
            array_push($htmlLines, '<span>' . basename($path) . '</span>');
            array_push($htmlLines, '</li>');
        }       
    }       
}

function getFormatLine($line, &$funcNameList, $funcColor) {
    // 因为返回类型形如List<Click>中的<，>被转义了，所以正则中;&被捕获
    // .主要用来处理全名称的对象类型
    // ,主要用于map类型
    // ?主要用于List<? >
    // []主要用于类似Invoice[]返回类型
    $funcName = "";
    if ( preg_match("/(public|private|protected)\s+([\w\.\s;&,\?\[\]]+?)\s+(\w+)\s*\(/", $line, $matches) ) {
        $funcName = $matches[3];
        if ($funcName != "main") {
            array_push($funcNameList, $funcName);
            $line = str_replace($funcName, "<span class='func'>$funcName</span>", $line);
        }
    }

    return array($line, $funcName);
}

// == API函数定义区

function update_file($input_file, $output_file, $name_value_arr) {
    reset_lib_result();

    try {
        $content = file_get_contents($input_file);
        foreach ($name_value_arr as $key => $val) {
            $content = str_replace($key, $val, $content);
        }

        file_put_contents($output_file, $content);
    
    } catch (Exception $e) {
        set_lib_result("1", $e->getMessage());  
        return false;
    }

    set_lib_result("0", "Info: build file($output_file) from template file($input_file) successfully.");
    return true;
}

function getTreeHtmlLines($srcCodeDir, $tree_id) {
    if (!is_dir($srcCodeDir)) return array("错误,无效的路径:\n$srcCodeDir");
    
    $htmlLines = array("<ul id='$tree_id' class='easyui-tree'>");
    loadDirTree($srcCodeDir, $htmlLines);
    array_push($htmlLines, '</ul>');

    return $htmlLines;
}

function getCodeDirPath($prodName, $svnAddr) {
    list($svnPath, $version) = explode("@", $svnAddr);
    return md5($prodName) . "/$version"; 
}

function getFileContent($filePath, $funcColor) {
    $type = "error";
    $fileExtenstion  = strtolower( pathinfo($filePath, PATHINFO_EXTENSION) );
    $tempArray = array("错误, 不能打开该类型文件(如有疑问，请联系张培修复!)");
    // *** 类型请用小写字母 ***
    $textPostfixList = Array("txt", "java", "properties", "property", "json", "xml", "sh", "bat", "cmd",
                                "sql", "py", "pl", "cpp", "c", "rb", "php", "html", "js", 
                                "jsp", "css", "template", "online", "mf");
    $imgPostfixList  = Array("gif", "jpg", "bmp", "png", "ico");
    $linkPostfixList = array("docx", "doc", "pdf", "ppt", "pptx");
    $funcNameList = Array();

    if (in_array($fileExtenstion, $textPostfixList)) {
        $tempArray = Array();
        $lines     = file($filePath);

        foreach ($lines as $line) {
            $line = htmlspecialchars($line);
            $arr = array($line, "");
            if ($fileExtenstion == "java") {
                $arr = getFormatLine($line, $funcNameList, $funcColor);
            }
            array_push($tempArray, $arr);
        }
        $type = "text";

    } elseif (in_array($fileExtenstion, $imgPostfixList)) {
        $type = "image";
        $filePath = str_replace("/disk2/qatest/svn_code/qa/WebFramework/htdocs", "", $filePath);
        $tempArray = array("<img src='$filePath'/>");

    } elseif (in_array($fileExtenstion, $linkPostfixList)) {
        $type = "image";
        $filePath = str_replace("/disk2/qatest/svn_code/qa/WebFramework/htdocs", "", $filePath);
        $tempArray = array("<a href='$filePath' target=_blank>下载</a>");
    }

    return array("type"    => $type,
                "lines"    => $tempArray,
                "funcNames" => $funcNameList);
}
