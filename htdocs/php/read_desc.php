<?php

$desc_file = $_GET["path"];
if ($desc_file == "../readme.tts") 
    $desc_file = "../readme.tts2";

if (file_exists($desc_file)) {
    $content = file_get_contents($desc_file);
    echo "<pre>$content</pre>";
} else {
    echo "";
}
