<?php
    $sponsor_id = $_POST["sponsor_id"];
    $tables_str = $_POST["table_list"];
    $conf_str   = $_POST["conf_str"];
    $useReadable = $_POST["readable"];
    $cmd = "cd ../python; ./TableHtmlWriter.py $sponsor_id $tables_str $conf_str $useReadable";
    system($cmd);
