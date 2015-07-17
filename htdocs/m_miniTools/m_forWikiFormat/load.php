<?php

chdir("./data");
$filenames = glob("*");
foreach (array_reverse($filenames) as $filename) {
    echo "<input type='radio' name='log' data-filename='$filename' /> $filename", "<br>\n";
}
