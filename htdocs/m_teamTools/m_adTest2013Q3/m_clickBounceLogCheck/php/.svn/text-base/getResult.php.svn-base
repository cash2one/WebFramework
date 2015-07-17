<?php

$cb_log = strtolower($_POST["cb"]);
$click_log = strtolower($_POST["click"]);

$bid_result     = Array();
$new_bid_result = Array();
$click_log_result = Array();
$expected_log_keys = Array(
    "bid" => 1, 
    "position_id" => 1, 
    "width" => 1, 
    "height" => 1, 
    "cpa" => 1, 
    "bidder" => 2, 
    "clicked_item" => 2, 
    "type" => 2, 
    "crowd" => 2, 
    "item" => 2, 
    "usercategory" => 2, 
    "page" => 2
);

$lines = explode("\n", $cb_log);

$found_type = "";
foreach ($lines as $line) {
    if ($found_type == "bidresult") {
        if (strstr($line, "newbidresult") == true) {
            $found_type = "newbidresult";
            continue;
        }
    }

    if ($found_type == "") {
        if (strstr($line, "bidresult") == true)
            $found_type = "bidresult";
        continue;
    }


    if (strstr($line, ":") == false || strstr($line, "id_in_page") == true)
        continue;

    $line = str_replace(" ", "", $line);
    list($key, $val) = explode(":", $line, 2);
    
    $val = trim($val, '"');
    if ($found_type == "bidresult") {
       $bid_result[$key] = $val; 
    } else {
       $new_bid_result[$key] = $val; 
    }
}

preg_match_all("/(textmap)={(.*?)}|(\w+time)=(.*?m)|(\w+)=([^,\]]+)/", $click_log, $ret_array);
$ret_array = $ret_array[0];

foreach ($ret_array as $key_val_str) {
    $key_val_str = trim($key_val_str);
    list($key, $val) = explode("=", $key_val_str, 2);
    if ($key == "textmap") {
        preg_match_all("/(\w+)=([^,}]+)/", $val, $ret_arr2);
        $ret_arr2 = $ret_arr2[0];
        foreach ($ret_arr2 as $key_val) {
            list($k1, $v1) = explode("=", $key_val);    
            $click_log_result[$k1] = $v1;
        }
    } elseif ($key == "superkeyword") {
        $val = str_replace("#&@!", ",", $val);
        $temp_array = json_decode($val, true);
        $click_log_result = array_merge($click_log_result, $temp_array);
    } else {
        $click_log_result[$key] = $val;
    }
}


// 字段说明：https://dev.corp.youdao.com/outfoxwiki/Advertisement/DSP/LogSpecification#head-2a8928798d7e5d0865ffd4bfe54fd83d2c2c5c57

$key_set = Array();
collect_keys($key_set, $bid_result);
collect_keys($key_set, $new_bid_result);
collect_keys($key_set, $click_log_result);
collect_keys($key_set, $expected_log_keys);

$lines = Array();
array_push($lines, "<pre><table border='1'>");
array_push($lines, "<tr><th>字段名</th><th>Bid Result</th><th>New Bid Result</th><th>Click Log (<a target=_blank href='https://dev.corp.youdao.com/outfoxwiki/Advertisement/DSP/LogSpecification#head-2a8928798d7e5d0865ffd4bfe54fd83d2c2c5c57'>DSP日志规范</a>)</th></tr>");
foreach ($key_set as $key) {
    $bid_val = get($bid_result, $key); 
    $new_bid_val = get($new_bid_result, $key);
    $log_val = get($click_log_result, $key);
    $must_val = get($expected_log_keys, $key);
    if ($must_val == 1) {
        array_push($lines, "<tr><td><font color='red'><b>$key</b></font></td><td>$bid_val</td><td>$new_bid_val</td><td>$log_val</td></tr>");

    } elseif ($must_val == 2) {
        array_push($lines, "<tr><td><font color='green'><b>$key</b></font></td><td>$bid_val</td><td>$new_bid_val</td><td>$log_val</td></tr>");

    } else {
        array_push($lines, "<tr><td>$key</td><td>$bid_val</td><td>$new_bid_val</td><td>$log_val</td></tr>");

    }
}
array_push($lines, "</table></pre>");
echo implode("\n", $lines);


function collect_keys(&$key_set, $inputArray) {
    foreach ($inputArray as $key => $val) {
        if (! in_array($key, $key_set)) {
            array_push($key_set, $key);
        }
    }
}

function get($array, $key) {
    if (array_key_exists($key, $array)) {
        return $array[$key]; 
    }
    return "";
}
