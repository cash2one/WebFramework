<?php

$url = $_GET["bounce"];
header("Location: $url", true, 302);
$cookie_value = "pei thanks you";
setcookie("vid", $cookie_value, time() + 3600*24, "/", "youdao.com");

?>
