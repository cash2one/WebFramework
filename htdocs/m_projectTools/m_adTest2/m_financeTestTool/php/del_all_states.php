<?php

$conf_file  = $_POST["conf_file"];
$py_file = "../python/del_all_states.py";
$cmd = "$py_file $conf_file";
system($cmd);
