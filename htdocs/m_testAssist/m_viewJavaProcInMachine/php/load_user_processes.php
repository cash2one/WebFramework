<?php

date_default_timezone_set("PRC");

$rows = Array();

$machine_list_str = substr($_GET["machine_list"], 0, -1);
// $machine_list_str = substr("qt101,", 0, -1);
$date = strftime("%Y%m%d", time());
$ref_dir = "../../m_ProcessPerfMonitor/perf_results";

$machine_list = explode(",", $machine_list_str);
foreach ($machine_list as $machine_name) {
    $log_file = $ref_dir . "/" . $machine_name . "/" . $date . "/*-*.log";
    $log_files = glob($log_file);
    foreach ($log_files as $log_file) {
        $last_line = `tail -n 1 $log_file`;
        list($timestamp_milli, $other) = explode(":", $last_line);
        $timestamp = $timestamp_milli / 1000;
        if ($timestamp + 120 > time()) {

            list($pid, $other)= explode("-", basename($log_file));
            list($user, $other) = explode(".", $other);

            $time_readable = strftime("%Y-%m-%d %H:%M:%S", $timestamp);

            array_push($rows, "<tr><td>$machine_name</td><td>$time_readable</td><td style='width:70%;'>$user</td><td>$pid</td></tr>");
        }
    }
}

sort($rows);
array_unshift($rows, "<tr><th>机器名</th><th>上次扫描时间</th><th>用户</th><th>PID</th></tr>");
echo implode("\n", $rows);
