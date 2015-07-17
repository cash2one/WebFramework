<?php

$hostname = $_GET["hostname"];
$date_str = $_GET["date"];
$proc_name = $_GET["proc_name"];

/*
$hostname = "nc109";
$date_str = "20130708";
$proc_name = "disk1";
*/

$proc_name = str_replace("-alive", "", $proc_name);
$proc_name = str_replace("-stop", "", $proc_name);

$file_path = "../perf_results/$hostname/$date_str/$proc_name.log";
$lines = file($file_path);

$file_cmd_path = "../perf_results/$hostname/$date_str/$proc_name.cmd";
$content = "";

$retArray = Array();
if ($proc_name == "machine") {
    $retArray["machine"] = Array(
        "label" => "load",
        "data" => Array(),
    );

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == "") continue;
        list($timestamp, $val) = explode(":", $line);
        $timestamp = $timestamp * 1;
        $val = (float)$val;
        array_push($retArray["machine"]["data"], Array($timestamp, $val));
    }

} elseif ($proc_name == "vmstat") {
    #r, b, swpd, free, buff, cache, si, so, bi, bo, In, cs, us, sy, id, wa, st
    $retArray["r"] = Array(
        "label" => "procs.r",
        "data" => Array(),
    );
    $retArray["b"] = Array(
        "label" => "procs.b",
        "data" => Array(),
    );
    $retArray["swpd"] = Array(
        "label" => "mem.swpd(GB)",
        "data" => Array(),
    );
    $retArray["free"] = Array(
        "label" => "mem.free(GB)",
        "data" => Array(),
    );
    $retArray["buff"] = Array(
        "label" => "mem.buff(GB)",
        "data" => Array(),
    );
    $retArray["cache"] = Array(
        "label" => "mem.cache(GB)",
        "data" => Array(),
    );
    $retArray["si"] = Array(
        "label" => "swap.si(GB)",
        "data" => Array(),
    );
    $retArray["so"] = Array(
        "label" => "swap.so(GB)",
        "data" => Array(),
    );
    $retArray["bi"] = Array(
        "label" => "io.bi",
        "data" => Array(),
    );
    $retArray["bo"] = Array(
        "label" => "io.bo",
        "data" => Array(),
    );
    $retArray["in"] = Array(
        "label" => "system.in",
        "data" => Array(),
    );
    $retArray["cs"] = Array(
        "label" => "system.cs",
        "data" => Array(),
    );
    $retArray["us"] = Array(
        "label" => "cpu.us(%)",
        "data" => Array(),
    );
    $retArray["sy"] = Array(
        "label" => "cpu.sy(%)",
        "data" => Array(),
    );
    $retArray["id"] = Array(
        "label" => "cpu.id(%)",
        "data" => Array(),
    );
    $retArray["wa"] = Array(
        "label" => "cpu.wa(%)",
        "data" => Array(),
    );
    $retArray["st"] = Array(
        "label" => "cpu.st(%)",
        "data" => Array(),
    );

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == "") continue;
        $line = str_replace(" ,", "", $line); // deal with dirty data
        $fields = preg_split("/:|,/", $line);
        $timestamp = $fields[0] * 1;
        #r, b, swpd, free, buff, cache, si, so, bi, bo, In, cs, us, sy, id, wa, st
        $r = (float)$fields[1];
        $b = (float)$fields[2];
        $swpd = (float)$fields[3] / 1048576.0;
        $free = (float)$fields[4] / 1048576.0;
        $buff = (float)$fields[5] / 1048576.0;
        $cache = (float)$fields[6] / 1048576.0;
        $si = (float)$fields[7] / 1048576.0;
        $so = (float)$fields[8] / 1048576.0;
        $bi = (float)$fields[9]; 
        $bo = (float)$fields[10];
        $in = (float)$fields[11];
        $cs = (float)$fields[12];
        $us = (float)$fields[13];
        $sy = (float)$fields[14];
        $id = (float)$fields[15];
        $wa = (float)$fields[16];
        $st = (float)$fields[17];
        array_push($retArray["r"]["data"], Array($timestamp, $r));
        array_push($retArray["b"]["data"], Array($timestamp, $b));
        array_push($retArray["swpd"]["data"], Array($timestamp, $swpd));
        array_push($retArray["free"]["data"], Array($timestamp, $free));
        array_push($retArray["buff"]["data"], Array($timestamp, $buff));
        array_push($retArray["cache"]["data"], Array($timestamp,$cache));
        array_push($retArray["si"]["data"], Array($timestamp, $si));
        array_push($retArray["so"]["data"], Array($timestamp, $so));
        array_push($retArray["bi"]["data"], Array($timestamp, $bi));
        array_push($retArray["bo"]["data"], Array($timestamp, $bo));
        array_push($retArray["in"]["data"], Array($timestamp, $in));
        array_push($retArray["cs"]["data"], Array($timestamp, $cs));
        array_push($retArray["us"]["data"], Array($timestamp, $us));
        array_push($retArray["sy"]["data"], Array($timestamp, $sy));
        array_push($retArray["id"]["data"], Array($timestamp, $id));
        array_push($retArray["wa"]["data"], Array($timestamp, $wa));
        array_push($retArray["st"]["data"], Array($timestamp, $st));
    }

} elseif ($proc_name == "network") {
    $retArray["rxpck"] = Array(
        "label" => "rxpck_rate",
        "data" => Array(),
    );
    $retArray["txpck"] = Array(
        "label" => "txpck_rate",
        "data" => Array(),
    );
    $retArray["rxkB"] = Array(
        "label" => "rxkB_rate(kb)",
        "data" => Array(),
    );
    $retArray["txkB"] = Array(
        "label" => "txkB_rate(kb)",
        "data" => Array(),
    );
    $retArray["rxcmp"] = Array(
        "label" => "rxcmp_rate",
        "data" => Array(),
    );
    $retArray["txcmp"] = Array(
        "label" => "txcmp_rate",
        "data" => Array(),
    );
    $retArray["rxmcst"] = Array(
        "label" => "rxmcst_rate",
        "data" => Array(),
    );

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == "") continue;
        $fields = preg_split("/:|,/", $line);
        if (count($fields) != 8) continue;

        $timestamp = $fields[0] * 1;
        $rxpck = (float)$fields[1];
        $txpck = (float)$fields[2];
        $rxkB = (float)$fields[3];
        $txkB = (float)$fields[4];
        $rxcmp = (float)$fields[5];
        $txcmp = (float)$fields[6];
        $rxmcst = (float)$fields[7];

        array_push($retArray["rxpck"]["data"], Array($timestamp, $rxpck));
        array_push($retArray["txpck"]["data"], Array($timestamp, $txpck));
        array_push($retArray["rxkB"]["data"], Array($timestamp, $rxkB));
        array_push($retArray["txkB"]["data"], Array($timestamp, $txkB));
        array_push($retArray["rxcmp"]["data"], Array($timestamp, $rxcmp));
        array_push($retArray["txcmp"]["data"], Array($timestamp, $txcmp));
        array_push($retArray["rxmcst"]["data"], Array($timestamp, $rxmcst));
    }

} elseif (in_array($proc_name, Array("disk1", "disk2", "disk3", "disk4", "disk5", "disk6"))) {
    $retArray["tps"] = Array(
        "label" => "tps",
        "data" => Array(),
    );
    $retArray["kB_read_rate"] = Array(
        "label" => "kB_rd_rate",
        "data" => Array(),
    );
    $retArray["kB_wrtn_rate"] = Array(
        "label" => "kB_wr_rate",
        "data" => Array(),
    );

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == "") continue;
        $fields = preg_split("/:|,/", $line);
        $timestamp = $fields[0] * 1;
        $tps = (float)$fields[1];
        $kb_rd = (float)$fields[2];
        $kb_wr = (float)$fields[3];
        array_push($retArray["tps"]["data"], Array($timestamp, $tps));
        array_push($retArray["kB_read_rate"]["data"], Array($timestamp, $kb_rd));
        array_push($retArray["kB_wrtn_rate"]["data"], Array($timestamp, $kb_wr));
    }

} else {
    $retArray["res"] = Array(
        "label" => "res(G)",
        "data" => Array(),
    );
    $retArray["cpu"] = Array(
        "label" => "cpu(%)",
        "data" => Array(),
    );
    $retArray["mem"] = Array(
        "label" => "mem(%)",
        "data" => Array(),
    );
    $retArray["thread_cnt"] = Array(
        "label" => "thread_cnt",
        "data" => Array(),
    );
    $retArray["fd_cnt"] = Array(
        "label" => "fd_cnt",
        "data" => Array(),
    );
    $retArray["socket_cnt"] = Array(
        "label" => "socket_cnt",
        "data" => Array(),
    );

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == "") continue;
        $fields = preg_split("/:|,/", $line);
        for($i = count($fields); $i < 7; $i++) { # should update the max_value when add new monitor object
            array_push($fields, 0);
        }

        $timestamp = $fields[0] * 1;
        $res = (float)$fields[1];
        $cpu = (float)$fields[2];
        $mem = (float)$fields[3];
        $thread_cnt = (int)$fields[4];
        $fd_cnt = (int)$fields[5];
        $socket_cnt = (int)$fields[6];
        array_push($retArray["res"]["data"], Array($timestamp, $res));
        array_push($retArray["cpu"]["data"], Array($timestamp, $cpu));
        array_push($retArray["mem"]["data"], Array($timestamp, $mem));
        array_push($retArray["thread_cnt"]["data"], Array($timestamp, $thread_cnt));
        array_push($retArray["fd_cnt"]["data"], Array($timestamp, $fd_cnt));
        array_push($retArray["socket_cnt"]["data"], Array($timestamp, $socket_cnt));
    }

    $content = "<b>[CMD]: </b>" . file_get_contents($file_cmd_path);
}

$retArrayObj = Array($content, $retArray);

echo json_encode($retArrayObj);
