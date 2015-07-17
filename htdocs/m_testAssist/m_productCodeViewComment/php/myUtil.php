<?php

function getTreeHtmlStr($dir) {

    function getResult($dir, &$retArray) {
        foreach (glob($dir . "/*") as $filePath) {
            if (is_dir($filePath)) {
                array_push($retArray, '<li data-options="attributes:{file_path:\'\'}, state:\'closed\'">');
                array_push($retArray, '<span>' . basename($filePath) . '</span>');
                array_push($retArray, '<ul>');
                getResult($filePath, $retArray);
                array_push($retArray, '</ul>');
                array_push($retArray, '</li>');
            } else {
                array_push($retArray, '<li data-options="attributes:{file_path:\'' . $filePath . '\'}">');
                array_push($retArray, '<span>' . basename($filePath) . '</span>');
                array_push($retArray, '</li>');
            }
        }
    }
    
    $retArray = Array();
    array_push($retArray, '<ul id="tt" class="easyui-tree">');
    getResult($dir, $retArray);
    array_push($retArray, '</ul>');

    return implode("\n", $retArray);
}

function getFileContent($filePath) {
    $funcNameList = Array();

    function getFormatLine($line, $linesCnt, $index, &$funcNameList) {
        ### notice: found global $funcNameList can't work
        $lineNoColor = "blue";
        $funcLine = false; 
        $funcName = "";

        // 因为返回类型形如List<Click>中的<，>被转义了，所以正则中;&被捕获
        // .主要用来处理全名称的对象类型
        // ,主要用于map类型
        // ?主要用于List<? >
        // []主要用于类似Invoice[]返回类型
        if ( preg_match("/(public|private|protected)\s+([\w\.\s;&,\?\[\]]+?)\s+(\w+)\s*\(/", $line, $matches) ) {
            $funcName = $matches[3];
            if ($funcName != "main") {
                array_push($funcNameList, $funcName);
                $line = preg_replace("/(public|private|protected)\s+([\w\.\s;&,\?\[\]]+?)\s+(\w+)\s*\(/", "\$1 \$2 <font color='red'>\$3 </font>(", $line);
                $funcLine = true;
            }
        }
        if ($linesCnt < 10)
            $line = sprintf("<font color='$lineNoColor'>%s</font> %s", $index, $line);
        elseif ($linesCnt < 100)
            $line = sprintf("<font color='$lineNoColor'>%2s</font> %s", $index, $line);
        elseif ($linesCnt < 1000)
            $line = sprintf("<font color='$lineNoColor'>%3s</font> %s", $index, $line);
        elseif ($linesCnt < 10000)
            $line = sprintf("<font color='$lineNoColor'>%4s</font> %s", $index, $line);

        if ($funcLine == true)
            return sprintf("<label id='func_name_%s'>%s</label>", $funcName, $line);
        else
            return $line;
    }

    $fileExtenstion = strtolower(substr(strrchr($filePath, '.'), 1));
    $fileContent    = "不能打开该类型文件(如有疑问，请联系张培修复!)";
    $textPostfixList = Array("txt", "java", "properties", "property", "json", "xml", "sh", "bat", "cmd", 
                                "sql", "py", "pl", "cpp", "c", "rb", "php", "html", "js", "jsp", "css", "template");
    $imgPostfixList  = Array("gif", "jpg", "bmp", "png", "ico");

    if (in_array($fileExtenstion, $textPostfixList)) {
        $lines    = file($filePath);
        $linesCnt = count($lines);
        $tempArray = Array();
        
        $index = 1;
        foreach ($lines as $line) {
            $line = str_replace("&", "&amp;", $line);
            $line = str_replace("<", "&lt;", $line);
            $line = str_replace(">", "&gt;", $line);

            $line = getFormatLine($line, $linesCnt, $index, $funcNameList);
            array_push($tempArray, $line);
            $index += 1; 
        }
        $fileContent = implode("", $tempArray);

    } elseif (in_array($fileExtenstion, $imgPostfixList)) {
        $fileContent = "<img src='$filePath'/>";
    }

    $funcNameList2 = array_count_values($funcNameList);
    $funcNameList3 = Array();
    foreach($funcNameList2 as $key => $val) {
        if ($val != 1) {
            array_push($funcNameList3, "<option name='$key'>$key($val)</option>");
        } else {
            array_push($funcNameList3, "<option name='$key'>$key</option>");
        }
    }
    return Array("<pre style='background:#C5FFC4'>\n$fileContent\n</pre>\n", implode("\n", $funcNameList3));
}
