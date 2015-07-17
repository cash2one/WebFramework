<?php
    $hostname = $_GET["hostname"];
    $handle = opendir("../perf_results/$hostname");
    $options = Array();
    while (false !== ($file = readdir($handle))) {
        if (substr($file, 0, 1) == ".") continue;
        array_push($options, "<option>$file</option>");
    }
    closedir($handle);

    sort($options);
    echo implode("\n", array_reverse($options));
