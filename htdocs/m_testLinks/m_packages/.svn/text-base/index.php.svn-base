<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/index.php.css" type="text/css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

    <script src="../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>
</head>

<body> 
<h3>组内常用软件包 (目录地址:/global/share/qa)  <a href="" id=alert>注意</a></h3>

<input type=radio name=pack_type for=accordion checked />第三方PACKAGES
<input type=radio name=pack_type for=accordion2 />测试组开发的PACKAGES

<br>
<br>

<?php
    $array_list = Array();
    $array_info_list = Array();
    $array_list2 = Array();
    $array_info_list2 = Array();
    function myscandir($dir, &$dir_list, &$info_list) {
        $my_dir = dir($dir);
        $dir_list[$dir] = Array();
        while ($file = $my_dir->read()) {
            $p = $dir . "/" . $file;
            if ($file == "." or $file == "..") {
                continue;
            }
            
            if ($file == "url.h") {
                if (file_exists($p)) {
                    $info_list[$dir] = file_get_contents($p);
                }
                continue;
            }
            
            if (is_dir($p)) {
                myscandir($p, $dir_list, $info_list);    

            }else {
                array_push($dir_list[$dir], $file . " (<a href='$p' name=path>路径</a> <a href='./php/download.php?file_path=" . urlencode($p) . "' name=download>下载</a>)");
            }
        }
    }
    myscandir("/global/share/qa/packages", $array_list, $array_info_list);
    $output_list = Array();
    $keys = array_keys($array_list);
    sort($keys);
    foreach ($keys as $dir) {
        $file_list = $array_list[$dir];
        $home_url_list = Array();
        if (array_key_exists($dir, $array_info_list)) {
            $temp_list = explode("\n", $array_info_list[$dir]);
            foreach ($temp_list as $line) {
                if (strstr($line, ":") == FALSE) continue;
                list($key, $val) = explode(":", $line, 2);
                $home_url_list[$key] = $val;
            }
        }
        sort($file_list);
        if (count($file_list) == 0) continue;
        array_push($output_list, "<h3>" . str_replace("/global/share/qa/packages/", "", $dir) . "</h3>");
        array_push($output_list, "<div>");
        array_push($output_list, "<p>");
        foreach ($home_url_list as $name => $url) {
            array_push($output_list, "<a href='$url' target=_blank><font color='green'>$name</font></a> ");
        }
        array_push($output_list, "<ul><li>");
        array_push($output_list, implode("</li><li>", $file_list));
        array_push($output_list, "</li>\n</ul>\n</p>");
        array_push($output_list, "</div>");
    }

    myscandir("/global/share/qa/dev-packages", $array_list2, $array_info_list2);
    $output_list2 = Array();
    $keys2 = array_keys($array_list2);
    sort($keys2);
    foreach ($keys2 as $dir) {
        $file_list = $array_list2[$dir];
        sort($file_list);
        if (count($file_list) == 0) continue;
        array_push($output_list2, "<h3>" . str_replace("/global/share/qa/dev-packages/", "", $dir) . "</h3>");
        array_push($output_list2, "<div>");
        array_push($output_list2, "<p>\n<ul><li>");
        array_push($output_list2, implode("</li><li>", $file_list));
        array_push($output_list2, "</li>\n</ul>\n</p>");
        array_push($output_list2, "</div>");
    }
?>

<div id="accordion">
<?php
    echo implode("\n", $output_list);
?>
</div>

<div id="accordion2">
<?php
    echo implode("\n", $output_list2);
?>
</div>

<script src="./js/index.php.js" type="text/javascript"></script>
</body>
</html>
