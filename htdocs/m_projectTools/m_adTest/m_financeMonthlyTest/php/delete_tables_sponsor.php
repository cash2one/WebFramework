<?php
    $sponsor_id = $_POST["sponsor_id"];
    $tables_str = $_POST["table_list"];
    $conf_str   = $_POST["conf_str"];
    $cmd = "cd ../python; ./TableDelete.py $sponsor_id $tables_str $conf_str";
    exec($cmd, $line, $ret);
    echo $ret;
