<?php

include("load_status.php");
$name = $_POST["name"];
$filename = md5($name);

$cmd = "cd ../pyLib/; python ./bin/read.py '$filename'";
exec($cmd, $lines, $ret);
if ($ret == 0) {
    echo "save status($name) successfully";
} else {
    echo "save status($name) failed";
}
