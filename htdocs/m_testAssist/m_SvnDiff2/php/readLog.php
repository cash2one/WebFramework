<?php
   
define("LOG_FILE", "../log/log.txt");
define("COUNT", 50);

if (file_exists(LOG_FILE)) {
    $lines = array_reverse(file(LOG_FILE));
    array_splice($lines, COUNT);
    foreach ($lines as $line) {
        echo str_replace("</tr>", "<td><a href=''>apply</a></td></tr>", $line);
    }

} else {
    echo "";
}
