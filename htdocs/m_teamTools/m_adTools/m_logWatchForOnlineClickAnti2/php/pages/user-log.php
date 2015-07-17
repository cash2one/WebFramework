<?php
   
define("LOG_FILE", "../../data/log.txt");
define("COUNT", 50);

echo "<table border='1'>";
echo "<tr><th>查询日期</th><th>Ldap</th><th>过滤字符串</th></tr>";
if (file_exists(LOG_FILE)) {
    $lines = array_reverse(file(LOG_FILE));
    array_splice($lines, COUNT); 
    echo implode("", $lines);

} else {
    echo "";
}
echo "</table>";
