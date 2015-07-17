<?php

define("CODEDB", "../data/code_db");
define("COUNT_IN_ONE_PAGE", 12);

$language = $_POST['language'];
$search_word = $_POST['s_word'];

if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    $error_msg = "";
    $result = @sqlite_query($db, 'select id, title, language from code_info', SQLITE_ASSOC, $error_msg);
    if (! $result) {
        echo "<li>ERROR: $error_msg></li>";
        exit(1);
    }

    $ret_array = sqlite_fetch_all($result); 
    $ret_array = array_reverse($ret_array);

    $index = 0;
    foreach ($ret_array as $row_array) {
        if ($language != "all" && $language != "") {
            if ($row_array["language"] != $language) {
                continue;
            }
        }
        if ($search_word != "") {
            if (strpos(strtolower($row_array["title"]), strtolower($search_word)) === false) {
                continue;
            }
        }

        $first_class = "";
        if ($index % COUNT_IN_ONE_PAGE == 0) $first_class = "first";
        $index ++;

        printf("<li class='url-info $first_class' name='%s'>%s </li>\n", $row_array["id"], $row_array["title"]);
    }
    
} else {
    echo "<li>$sqliteerror</li>";
}
