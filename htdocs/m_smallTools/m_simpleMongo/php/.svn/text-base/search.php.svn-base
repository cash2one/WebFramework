<?php

$login_info = Array(Array(), Array(), Array());
$file = "../data/login.txt";

if (file_exists($file)) {
    $lines = file($file);

    foreach ($lines as $line) {
        $line = trim($line);
        list($host, $port, $user) = explode("", $line);
        array_push($login_info[0], $host);
        array_push($login_info[1], $port);
        array_push($login_info[2], $user);
    }

    print_r($login_info);
    $login_info[0] = array_unique($login_info[0]);
    $login_info[1] = array_unique($login_info[1]);
    $login_info[2] = array_unique($login_info[2]);
}

echo json_encode($login_info);
