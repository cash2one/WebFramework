<?php

/*
搜索，频道、线下直销，omedia： 1,2
联盟：4
邮箱：8
词典：16
*/

date_default_timezone_set("Asia/Shanghai");

// get from the font-end
$service_type = $_GET["service_type"];
$username = stripslashes($_GET["username"]);
$password = stripslashes($_GET["password"]);

/*
$service_type = "resin_union";
$username = "zhangpei";
$password = "";
*/

// ========================== Variables Definitions ==============================

// Constant Variables
define("CONF_DIR", "../conf/");
define("DATA_DIR", "../online_access_log/");
define("LINE_CNT", 1000);

$log_date_str = strftime("%Y-%m-%d", time() - 60 * 60 * 24);
$log_date_str2 = strftime("%Y%m%d", time() - 60 * 60 * 24);

// used to return to the front as json format
$ret_array = Array(
    "status" => 0,
    "message" => "",
    "lines" => Array(),
);

$impr_resin_list = Array(
    "resin_mail", 
    "resin_union", 
    "resin_dict", 
    "resin_channel", 
    "resin_omedia"
);
$type_file_list = Array(
    "resin_mail" => "eadm_resin.conf",
    "resin_union" => "eadu_resin.conf",
    "resin_dict" => "eadd_resin.conf",
    "resin_channel" => "eadc_right_resin.conf",
    "resin_omedia" => "omedia_resin.conf",
    "click_mail" => "click_resin.conf",
    "click_search" => "click_resin.conf",
    "click_union" => "click_resin.conf",
    "click_dict" => "click_resin.conf",
);

// ========================== Functions Definitions ==============================
function get_access_log_info() {
    global $service_type, $type_file_list, $log_date_str, $log_date_str2;

    $date_str = $log_date_str;

    if ($service_type == "resin_channel") {
        $date_str = $log_date_str2;
    }

    $conf_file = CONF_DIR . $type_file_list[$service_type];
    $lines = file($conf_file);
    $count_lines = count($lines);
    $idx = rand(0, $count_lines - 1);
    $conf_str = trim($lines[$idx]);
    list($host, $access_log_path) = explode(":", $conf_str); // path without date
    return Array($host, $access_log_path . "." . $date_str . "*");
}

function get_cmd($host, $access_log_path) {
    global $service_type, $impr_resin_list;
    $count = LINE_CNT;

    $cmd = "";
    if (in_array($service_type, $impr_resin_list)) {
        if ($service_type == "resin_channel") {
            $count = (int)($count / 3);
        }
        $cmd = "ssh $host 'head -$count $access_log_path'";

    } else if ($service_type == "click_mail") {
        $count = $count * 20;
        $cmd = "ssh $host 'head -$count $access_log_path|grep s=8'";
    
    } else if ($service_type == "click_search") {
        $cmd = "ssh $host \\\"head -$count $access_log_path|grep 's=1\\\|2'|grep -v s=16\\\"";

    } else if ($service_type == "click_union") {
        $cmd = "ssh $host 'head -$count $access_log_path|grep s=4'";

    } else if ($service_type == "click_dict") {
        $count = $count * 10;
        $cmd = "ssh $host 'head -$count $access_log_path|grep s=16'";
    }

    return $cmd;
}

function get_log_lines($host, $access_log_path, &$ret_lines) {
    global $service_type, $log_date_str, $username, $password, $impr_resin_list;
    $local_file = DATA_DIR . $service_type .".log." . $log_date_str;

    if (! file_exists($local_file)) {
        $cmd = get_cmd($host, $access_log_path);
        $exp_temp_file = "./query_log.exp.template";
        $exp_file = "query_log.exp";
        $file_content = file_get_contents($exp_temp_file);
        $file_content = str_replace('$current_file$', $exp_file, $file_content);
        $file_content = str_replace('$username$', $username, $file_content);
        $file_content = str_replace('$password$', $password, $file_content);
        $file_content = str_replace('$cmd$', $cmd, $file_content);
        file_put_contents($exp_file, $file_content);

        $cmd = "expect $exp_file";
        exec($cmd, $lines, $ret);
        $max_idx = count($lines) - 1;
        $message = $lines[$max_idx];

        if ($ret != 0 || substr($message, 0, 3) == "su:") {
            $ret = 1;
            return Array($ret, $message);

        } else { // else-2
            $urls = Array();
            $isImprResin = in_array($service_type, $impr_resin_list);

            foreach ($lines as $line) {
                $url = "";
                if ($isImprResin == true){
                    preg_match("/GET (\/imp\/\w+\.s[^ ]*)/", $line, $matches);
                    if ($matches) {
                        $url = $matches[1];
                    }

                } else {
                    preg_match("/GET (\/clk\/\w+\.s[^ ]*)/", $line, $matches);
                    if ($matches) {
                        $url = $matches[1];
                    }
                }

                if ($url != "") {
                    array_push($urls, $url);
                }
            }

            // save result to file
            $file_str = implode("\n", $urls);
            file_put_contents($local_file, $file_str);

        } // end of else-2
    } // end of else-1

    // read from file
    foreach (file($local_file) as $line) {
        array_push($ret_lines, trim($line));
    }
    return Array(0, "");

} // end of fucntion

// ========================== Main Logic ==============================
$ret_lines = Array();
list($host, $access_log_path) = get_access_log_info();
list($status, $message) = get_log_lines($host, $access_log_path, $ret_lines);

$ret_array["status"] = $status;
$ret_array["message"] = $message;
$ret_array["lines"] = $ret_lines;
echo json_encode($ret_array);
