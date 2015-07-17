<?php

define("URLDB", "../data/url_db");

$url = $_POST["url"];

if ($db = sqlite_open(URLDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select desc from url_info where url="' . $url .'"', SQLITE_ASSOC);
    $ret_array = sqlite_fetch_array($result); 
    echo $ret_array["desc"] == "" ? "无描述" : "<pre>" . str_replace("\n", "<br>", $ret_array["desc"]) . "</pre>";

} else {
    echo "$sqliteerror";
}
