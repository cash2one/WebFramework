<?php
    date_default_timezone_set("PRC");
    $hostname = $_GET["hostname"];
    $date_str = $_GET["date"];
    $ignore_vaquero = (int)$_GET["ignore_vaquero"];
    /*
    $hostname = "nc109";
    $date_str = "20130708";
    $ignore_vaquero = 0;
    */

    $handle = opendir("../perf_results/$hostname/$date_str");
    $options = Array();
    $options_stop = Array();
    while (false !== ($file = readdir($handle))) {
        if (substr($file, 0, 1) == "." || substr($file, -4) == ".cmd") continue;

        if ($ignore_vaquero == 1 && !in_array(substr($file, 0, -4), Array("machine", "disk1", "disk2", "disk3", "disk4", "disk5", "disk6", "vmstat", "network"))) {
            $cmd_file = "../perf_results/$hostname/$date_str/" . substr($file, 0, -4) . ".cmd";
            $cmd_content = file_get_contents($cmd_file);
            if (strpos($cmd_content, "toolbox.manager.tools.AnalyzerService") != false) continue;
        }

        $today_date_str = strftime("%Y%m%d", time());
        if (file_exists("../perf_results/$hostname/$today_date_str/$file")) {
            $file_m_time = filemtime("../perf_results/$hostname/$today_date_str/$file");
            $cur_time = time();
            if ($file_m_time + 120 < $cur_time) {
                $flag = "stop";
            } else {
                $flag = "alive";
            }
        } else {
            $flag = "stop";
        } 

        if (in_array(substr($file, 0, -4), Array("machine", "disk1", "disk2", "disk3", "disk4", "disk5", "disk6", "vmstat", "network"))) {
            array_push($options, "<option name='$file'>" . substr($file, 0, -4) . "</option>");
        } else {
            if ($flag == "alive") {
                array_push($options, "<option name='$file'>" . substr($file, 0, -4) . "-$flag" . "</option>");
            } else {
                array_push($options_stop, "<option name='$file'>" . substr($file, 0, -4) . "-$flag" . "</option>");
            }
        }
    }
    closedir($handle);
    
    sort($options);
    sort($options_stop);
    $options = array_merge($options_stop, $options);
    echo implode("\n", array_reverse($options));
