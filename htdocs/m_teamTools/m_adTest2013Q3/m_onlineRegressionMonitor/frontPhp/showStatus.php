<?php

include("interface.php");
$status = getStatus();
if ($status == "running") {
    $status = "<font color='red'>$status ...</font>";
} else {
    $status = "<font color='blue'>$status</font>";
}
echo "状态: ", $status;
