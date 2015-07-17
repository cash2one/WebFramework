<?php

$conf_file = $_POST["conf_file"];
$schema_dir = "../schema";
$schema_list_str = $_POST["schema_list_str"];
$py_file = "../python/show_table_content.py";

$cmd = "$py_file $conf_file $schema_dir $schema_list_str";
system($cmd);
