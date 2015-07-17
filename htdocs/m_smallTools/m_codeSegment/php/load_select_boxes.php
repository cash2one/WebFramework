<?php

define("CODEDB", "../data/code_db");

if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select language from code_info', SQLITE_ASSOC);
    $ret_array = sqlite_fetch_all($result); 

    $lang_list = Array();
    foreach ($ret_array as $row_array) {
        array_push($lang_list, $row_array["language"]);
    }
    foreach (array_unique($lang_list) as $lang) {
        echo "<input name='lang_type' type=radio for='$lang''>$lang";
    }
}
