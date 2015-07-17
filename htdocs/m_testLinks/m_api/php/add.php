<?php

$name = $_GET["name"];
$url  = $_GET["url"];

$name = str_replace("-", "_", $name);
file_put_contents("url.api.custom", "$name - $url\n", FILE_APPEND);
