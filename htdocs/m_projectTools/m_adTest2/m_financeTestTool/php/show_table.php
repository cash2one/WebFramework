<?php

$conf_file = $_POST["conf_file"];
$py_file = "../python/show_schema_names.py";
$cmd = "$py_file $conf_file";
exec($cmd, $ret_array);

foreach ($ret_array as $schema_name) {
    list($type, $table_name) = explode("_", $schema_name, 2);
    echo "<input type=checkbox name=$schema_name for=$table_name> $table_name <br>";
}
