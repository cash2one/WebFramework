<?php

chdir("../data");
$prod_line_list = glob("*");

echo "<table border='1'>\n";
echo "<tr><th>产品线列表</th></tr>\n";
foreach ($prod_line_list as $prod_line) {
    echo "<tr><td>$prod_line</td></tr>\n";
}
echo "</table>";
