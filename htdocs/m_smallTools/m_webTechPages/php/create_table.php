<?php
define("URLDB", "../data/url_db");
if ($db = sqlite_open(URLDB, 0666, $sqliteerror)) {
    sqlite_query($db, 'CREATE TABLE url_info (md5val varchar(32) primary key, url varchar(1024), title varchar(1024), desc varchar(10240), class varchar(200))');
} else {
    die($sqliteerror);
}
