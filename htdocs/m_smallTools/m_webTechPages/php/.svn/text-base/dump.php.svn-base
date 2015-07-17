<?php

define("URLDB", "../data/url_db");

if ($db = sqlite_open(URLDB, 0666, $sqliteerror)) {
    $result = sqlite_query($db, 'select * from url_info', SQLITE_ASSOC);
    var_dump(sqlite_fetch_all($result));
} else {
    echo "<tr><td>$sqliteerror</td></tr>";
}
