<?php
define("CODEDB", "../data/code_db");
if ($db = sqlite_open(CODEDB, 0666, $sqliteerror)) {
    sqlite_query($db, 'CREATE TABLE code_info (id integer primary key, title varchar(1024), content varchar(10240), language varchar(20))');
} else {
    die($sqliteerror);
}
