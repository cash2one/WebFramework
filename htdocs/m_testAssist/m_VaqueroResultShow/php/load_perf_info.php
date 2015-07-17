<?php

$user = $_GET["user"];
$user_dir = "../vaquero_data/$user";
$files = glob($user_dir . "/*");

foreach ($files as $file) {
    # show only 7 days' old file
    $mtime = filemtime($file);
    if (time() - $mtime > 7 * 24 * 60 * 60) continue;

    $filename = basename($file);
    list($cub, $prod, $type) = explode(":", $filename);
    echo "<tr><td>$cub</td><td>$prod</td><td>$type</td><td><input type=checkbox /></td></tr>";
}
