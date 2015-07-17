<?php

$conf_file = $_POST["conf_file"];
$state_name = trim($_POST["state_name"]);
$py_file = "../python/bakup_state.py";
$cmd = "$py_file $conf_file '$state_name'";
exec($cmd, $ret_array);
echo $ret_array[0];
