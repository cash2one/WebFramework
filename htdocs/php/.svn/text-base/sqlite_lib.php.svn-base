<?php

define("SQL_FILE_PATH", "access_info.sqlite");

function get_db() {
    $db_file_exists = file_exists(SQL_FILE_PATH);
    $db = sqlite_open(SQL_FILE_PATH, 0666, $sqliteerror);

    if (! $db)
        die($sqliteerror);
    if (! $db_file_exists)
        create_db($db);

    return $db;
}

function create_db($db) {
    sqlite_query($db, 'create table access_info (
                            id int primay key,
                            url varchar(200),
                            access_time varchar(30),
                            access_ldap varchar(20),
                            extra_field1 varchar(100),
                            extra_field2 varchar(200))'
                 );
}

function add_access($url, $ldap = "anonymous") {
    $db = get_db();
    $acc_time = time();
    $ret = @sqlite_query($db, "insert into access_info VALUES ('', '$url', '$acc_time', '$ldap', '', '')", $result_type, $err_msg);

    if (! $ret) {
        echo "输入数据($url)失败: $err_msg !";
    } else {
        echo "0";
    }

    return $ret;
}

function dump_db() {
    $db = get_db();
    $result = @sqlite_query($db, 'select * from access_info', SQLITE_ASSOC);
    var_dump(sqlite_fetch_all($result));
}

function load_popular_db_data($type = SQLITE_NUM) {
    $db = get_db();
    $result = sqlite_query($db, "select url, count(*) from access_info group by url", $type);
    $temp_arr = sqlite_fetch_all($result);
    $ret_arr = Array();
    foreach ($temp_arr as $sub_arr) {
        list($url, $cnt) = $sub_arr;
        $ret_arr[$url] = $cnt;
    }
    arsort($ret_arr);
    return Array(
        "hot" => array_slice($ret_arr, 0, 10),
        "good" => array_slice($ret_arr, 10, 10),
        "all" => $ret_arr,
    );
}

/*
add_access("http://www.baidu.com");
print_r(load_popular_db_data());
dump_db();
*/
