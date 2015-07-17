<?php

define("CODEDB", "../data/code_db");

$id = $_POST["id"];
// $title = "php中的timestamp";

if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select content from code_info where id="' . $id .'"', SQLITE_ASSOC);
    $ret_array = sqlite_fetch_array($result); 
    echo $ret_array["content"] == "" ? "内容为空" : "<pre>" . str_replace("\n", "<br>", $ret_array["content"]) . "</pre>";

} else {
    echo "$sqliteerror";
}
