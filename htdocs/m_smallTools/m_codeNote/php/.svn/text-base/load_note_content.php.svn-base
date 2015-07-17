<?php

$product = stripslashes($_GET["product"]);
$p_id = stripslashes($_GET["id"]);
$prod_dir_name = "../data/$product";

$lines = Array();

$files = glob($prod_dir_name . "/*.title");
foreach ($files as $file) {
    $file_name = basename($file); 
    $id = str_replace(".title", "_title", $file_name);
    
    if ($id == $p_id) {
        $file = str_replace(".title", ".content", $file);
        $content = file_get_contents($file);
        echo $content;
        break;
    }
}
