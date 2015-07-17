<?php

date_default_timezone_set("Asia/Shanghai");
define("LOG_FILE", "log.txt");

function getIP() { //获取IP   
    if ($_SERVER["HTTP_X_FORWARDED_FOR"])   
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];   
    else if ($_SERVER["HTTP_CLIENT_IP"])    
        $ip = $_SERVER["HTTP_CLIENT_IP"];   
    else if ($_SERVER["REMOTE_ADDR"])   
        $ip = $_SERVER["REMOTE_ADDR"];   
    else if (getenv("HTTP_X_FORWARDED_FOR"))    
        $ip = getenv("HTTP_X_FORWARDED_FOR");   
    else if (getenv("HTTP_CLIENT_IP"))   
        $ip = getenv("HTTP_CLIENT_IP");   
    else if (getenv("REMOTE_ADDR"))    
        $ip = getenv("REMOTE_ADDR");   
    else   
        $ip = "Unknown";   
    return $ip;   
}

$ip       = getIP();
$time_str = strftime("%Y-%m-%d %H:%M:%S", time());
$line     = "$time_str $ip\n";
file_put_contents(LOG_FILE, $line, FILE_APPEND);
