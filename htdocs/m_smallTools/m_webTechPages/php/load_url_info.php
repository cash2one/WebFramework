<?php

define("URLDB", "../data/url_db");
define("COUNT_IN_ONE_PAGE", 12);

$classname = $_POST['class'];
$search_word = $_POST['s_word'];
$type = $_POST["type"];

if ($db = sqlite_open(URLDB, 0666, $sqliteerror)) {
    $error_msg = "";
    $result = @sqlite_query($db, 'select url, title, class from url_info', SQLITE_ASSOC, $error_msg);
    if (! $result) {
        echo "<li>ERROR: $error_msg></li>";
        exit(1);
    }

    $ret_array = sqlite_fetch_all($result); 
    $ret_array = array_reverse($ret_array);

    $index = 0;
    foreach ($ret_array as $row_array) {
        if ($classname != "all" && $classname != "") {
            if (strpos($row_array["class"], $classname) === false) {
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

        if ($type == "show") {
            if ($row_array["title"] != "") {
                printf("<li class='url-info $first_class' name='%s'>%s (<a target=_blank href='%s'>访问</a>)</li>\n", $row_array["url"], $row_array["title"], $row_array["url"]);
            } else {
                printf("<li class='url-info $first_class' name='%s'>%s (<a target=_blank href='%s'>访问</a>)</li>\n", $row_array["url"], $row_array["url"], $row_array["url"]);
            }
        } else if ($type == "edit") {
            if ($row_array["title"] != "") {
                printf("<li class='url-info $first_class' name='%s'>%s (<a name='edit' href=''>编辑</a>)</li>\n", $row_array["url"], $row_array["title"]);
            } else {
                printf("<li class='url-info $first_class' name='%s'>%s (<a name='edit' href=''>编辑</a>)</li>\n", $row_array["url"], $row_array["url"]);
            }
        }
    }
    
} else {
    echo "<li>$sqliteerror</li>";
}
