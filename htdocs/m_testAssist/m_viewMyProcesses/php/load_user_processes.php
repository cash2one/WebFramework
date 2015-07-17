<?php

date_default_timezone_set("PRC");

$rows = Array();

$user = $_GET["user"];
$date = strftime("%Y%m%d", time());
$ref_dir = "../../m_ProcessPerfMonitor/perf_results";
$handle = opendir($ref_dir);
while (false !== ($machine_name = readdir($handle))) {
    if (substr($machine_name, 0, 1) == ".") continue;
    $user_log_file = $ref_dir . "/" . $machine_name . "/" . $date . "/*-$user.log";
    $log_files = glob($user_log_file);
    foreach ($log_files as $log_file) {
        $last_line = `tail -n 1 $log_file`;
        list($timestamp_milli, $other) = explode(":", $last_line);
        $timestamp = $timestamp_milli / 1000;
        if ($timestamp + 120 > time()) {
            $cmd_file = substr($log_file, 0, -4) . ".cmd";
            $cmd_content = file_get_contents($cmd_file);

            list($pid, $other)= explode("-", basename($cmd_file));

            $time_readable = strftime("%Y-%m-%d %H:%M:%S", $timestamp);

            array_push($rows, "<tr><td>$machine_name</td><td>$pid</td><td>$time_readable</td><td style='width:70%;'><pre>$cmd_content</pre></td></tr>");
        }
    }
}
closedir($handle);

sort($rows);
array_unshift($rows, "<tr><th>机器名</th><th>PID</th><th>上次扫描时间</th><th>进程命令</th></tr>");
echo implode("\n", $rows);
