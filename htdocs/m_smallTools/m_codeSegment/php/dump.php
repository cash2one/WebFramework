<?php

define("CODEDB", "../data/code_db");

if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select * from code_info', SQLITE_ASSOC);
    var_dump(sqlite_fetch_all($result));
} else {
    echo "<tr><td>$sqliteerror</td></tr>";
}
