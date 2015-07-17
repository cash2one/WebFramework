<?php

define("TEMP_DIR", "../tmp");
define("LOG_FILE", "../log/log.txt");

// ====== Global Vars ============
$reqStr = $_GET["req_str"];
$reqObj = json_decode(stripslashes($reqStr), true);

$retArray = Array(
    "ret" => 0, // 0 means pass
    "msg" => "",
    "results" => Array(),
);

// ====== Functions ============
function save_log($hostname, $ana_path, $prod, $serv_type, $cubeId) {
    $row = "<tr><td>$hostname</td><td>$ana_path</td><td>$prod</td><td>$serv_type</td><td>$cubeId</td><td><a href='' class='apply'>应用</a></td></tr>";
    file_put_contents(LOG_FILE, "$row\n", FILE_APPEND);
}

// convert yyyy-mm-dd hh:mm to mm/dd/yyyy,hh,mm
function get_time_str($time_str) {
    list($year, $mon, $day, $hour, $min) = preg_split("/-|:| /",  $time_str);
    return sprintf("%02d/%02d/%4d,%02d,%02d", $mon, $day, $year, $hour, $min);
}

// scp needed files
function scp_file($hostname, $file) {
    global $retArray;
    $local_file = TEMP_DIR . "/" . basename($file) . "." . time();
    $cmd = "scp $hostname:$file $local_file";
    exec($cmd . " 2>/dev/null", $lines, $ret);
    
    if ($ret != 0) {
        $retArray["ret"] = 1;
        $retArray["msg"] = "Error: 读取$hostname:$file 失败!";
        echo json_encode($retArray);
        exit(1);
    }

    return $local_file;
}

// parse analyzer.sh
function parse_analyzer_sh($ana_file) {
    $lines = file($ana_file);
    $config = null;
    $product = null;
    $serverType = null;
    $cubId = null;
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match("/config=\"([^\"]+)\"/", $line, $matches)) {
            $config = $matches[1];

        } else if (preg_match("/product=\"([^\"]+)\"/", $line, $matches)) {
            $product = $matches[1];

        } else if (preg_match("/serverType=\"([^\"]+)\"/", $line, $matches)) {
            $serverType = $matches[1];

        } else if (preg_match("/cubId=([^\"]+)/", $line, $matches)) {
            $cubId = $matches[1];
        }
    }

    return Array($config, $product, $serverType, $cubId);
}

function parse_xml_config_file($xml_file) {
    $name_list = Array();

    $file_content = file_get_contents($xml_file);
    $file_content = preg_replace("/\n/", "", $file_content);
    // remove comment lines
    $file_content = preg_replace("<!--.*?-->", "", $file_content);
    
    // find all keyset(s)
    preg_match_all("/<keyset.*?<\/keyset>/", $file_content, $matches);
    if (count($matches) != 0) {
        foreach ($matches[0] as $keyset_str) {
            $key_name = null;

            // extract name
            preg_match("/name=\"([^\"]+)\"/", $keyset_str, $names);
            if (count($names) != 0) {
               $key_name = $names[1]; 
            } else {
                // code to-do
            }

            // extract performance
            preg_match("/performance=\"(true)\"/", $keyset_str, $perfs);
            if (count($perfs) != 0) {
                array_push($name_list, $key_name . ".response");
            }

            // extract throughput
            preg_match("/throughput=\"(true)\"/", $keyset_str, $ths);
            if (count($ths) != 0) {
                array_push($name_list, $key_name . ".throughput");
            }

            // extract proportion 
            preg_match("/allowall=\"(false)\"/", $keyset_str, $ths);
            if (count($ths) != 0) {
                array_push($name_list, $key_name . ".proportion");
            }

            // extract type and countonly
            preg_match("/type=\"(\w+)\"/", $keyset_str, $types);
            if ($types[0] != "string") {

                preg_match("/countonly=\"(\w+)\"/", $keyset_str, $cntonly);
                if (count($cntonly) != 0) {
                    if ($cntonly[1] == "false") {
                        array_push($name_list, $key_name. ".proportion");
                        array_push($name_list, $key_name. ".throughput");
                    } else {
                        array_push($name_list, $key_name);
                    }
                }
            }
        }
    } else {
        // code to-do here
    }

    return $name_list;
}

// ====== Main Logic ============
$inputObjList = $reqObj["inputList"];
$machineInfoList = $reqObj["machine_info"];
$time_start = get_time_str($reqObj["time_start"]);
$time_end   = get_time_str($reqObj["time_end"]);
$time_type  = $reqObj["time_type"];

$url_list = Array();

foreach ($inputObjList as $ilist) {
    list($hostname, $ana_path, $prod, $serv_type, $cubeId) = $ilist;
    save_log($hostname, $ana_path, $prod, $serv_type, $cubeId);

    // copy analyzer.sh to tmp
    if (substr($ana_path, -3) == ".sh") { 
        $ana_local_file = scp_file($hostname, $ana_path);
    } else {
        $ana_local_file = scp_file($hostname, $ana_path . "/analyzer.sh");
    }

    // extract analyzer.sh
    list($config, $prod2, $serv_type2, $cubeId2) = parse_analyzer_sh($ana_local_file);

    // check params
    $ret = 0;
    $msg = "";
    if ($prod == "auto") {
        if ($prod2 == null) {
            $ret = 1;
            $msg = "Error：读取product失败!";
        } else {
            $prod = $prod2;
        }
    } 
    if ($serv_type == "auto") {
        if ($serv_type2 == null) {
            $ret = 1;
            $msg = "Error：读取serverType失败!";
        } else {
            $serv_type = $serv_type2;
        }
    } 
    if ($cubeId == "auto") {
        if ($cubeId2 == null) {
            $ret = 1;
            $msg = "Error：读取cubId失败!";
        } else if ($cubeId2 == "$1") {
            $cubeId = substr($hostname, 0, 5);
        } else {
            $cubeId = $cubeId2;
        }
    }
    if ($ret != 0) {
        $retArray["ret"] = 1;
        $retArray["msg"] = $msg;
        echo json_encode($retArray); 
        exit(1);
    }

    // scp config file
    if (substr($ana_path, -3) == ".sh") { 
        $conf_local_file = scp_file($hostname, dirname($ana_path) . "/" . $config);
    } else {
        $conf_local_file = scp_file($hostname, $ana_path . "/" . $config);
    }
    $key_name_list = parse_xml_config_file($conf_local_file);

    // for user-input perf keys
    foreach ($key_name_list as $key_name) {
        if ($time_type != "manual") {
            $url = "http://vaquero.corp.youdao.com//image?type=img&product=$prod&name=$cubeId&drawname=$key_name&cubtype=$serv_type&period=$time_type";
        } else {
            $url = "http://vaquero.corp.youdao.com//image?type=img&product=$prod&name=$cubeId&drawname=$key_name&cubtype=$serv_type&period=$time_type&start=$time_start&end=$time_end";
        }

        array_push($retArray["results"], $url);
    }

    // for machine perf keys
    foreach ($machineInfoList as $key_str) {
        $prod = substr($hostname, 0, 2);
        list($serv_type, $key_name) = explode(":", $key_str);
        if ($time_type != "manual") {
            $url = "http://vaquero.corp.youdao.com//image?type=img&product=$prod&name=$hostname&drawname=$key_name&cubtype=$serv_type&period=$time_type";
        } else {
            $url = "http://vaquero.corp.youdao.com//image?type=img&product=$prod&name=$hostname&drawname=$key_name&cubtype=$serv_type&period=$time_type&start=$time_start&end=$time_end";
        }

        array_push($retArray["results"], $url);
    }
}

echo json_encode($retArray);
