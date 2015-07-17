<?php

define("LOG_FILE", "../logs/log.txt");
define("COUNT", 50);

if (file_exists(LOG_FILE)) {
    $lines = array_reverse(file(LOG_FILE));
    array_splice($lines, COUNT);
    echo implode("", $lines);

} else {
    echo "";
}
