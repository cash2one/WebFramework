<?php
    $data = $_POST["data"];
    $file = "../python/conf/finance_deploy.conf"; 
    $ret = file_put_contents($file, $data);
    if ($ret == strlen($data)) {
        echo "0";
    }
