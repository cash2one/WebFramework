<?php

$file = stripslashes($_POST["file"]);

if (file_exists($file)) {
    echo str_replace("\n", "<br>", file_get_contents($file));
}

?>
