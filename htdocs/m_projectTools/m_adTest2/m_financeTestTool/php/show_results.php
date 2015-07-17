<?php

$conf_file = $_POST["conf_file"];
$s1_name = trim($_POST["s1_state"]);
$s2_name = trim($_POST["s2_state"]);
$py_file = "../python/show_results.py";
$cmd = "$py_file $conf_file '$s1_name' '$s2_name'";
system($cmd);
