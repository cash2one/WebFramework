<?php
   
define("LOG_FILE", "../log/log.txt");
define("COUNT", 50);

if (file_exists(LOG_FILE)) {
    $lines = array_reverse(file(LOG_FILE));
    array_splice($lines, COUNT);
    foreach ($lines as $line) {
        echo preg_replace("#(<tr><td>.*</td><td>.*</td>)<td>(.*)</td><td>(.*)</td></tr>#", "\$1<td><div name='old_ver'>\$2</div><div name='new_ver'>\$3</div></td><td><a href=''>apply</a></td></tr>", $line);
        //echo str_replace("</tr>", "<td><a href=''>apply</a></td></tr>", $line);
    }

} else {
    echo "";
}
