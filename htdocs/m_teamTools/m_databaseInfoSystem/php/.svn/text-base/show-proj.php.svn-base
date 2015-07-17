<?php

$prod_line = $_POST["prod_line"];
chdir("../data/$prod_line");
$proj_list = glob("*");

echo "<table border='1'>\n";
echo "<tr><th>服务/项目列表</th></tr>\n";
foreach ($proj_list as $proj) {
    echo "<tr><td>$proj</td></tr>\n";
}
echo "</table>";
