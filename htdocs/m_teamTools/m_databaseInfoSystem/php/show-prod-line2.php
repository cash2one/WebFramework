<?php

chdir("../data");
$prod_line_list = glob("*");

foreach ($prod_line_list as $prod_line) {
    echo "<option>$prod_line</option>\n";
}
