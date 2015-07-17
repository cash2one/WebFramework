<?php

define("SQL_FILE_PATH", "book_info.sqlite");

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
    sqlite_query($db, 'create table book_info (
                            target_name varchar(100) primary key,
                            book_name varchar(100), 
                            book_owner varchar(30),
                            book_ctime varchar(20),
                            book_tags varchar(100),
                            book_deleted int,
                            book_size int,
                            field varchar(200),
                            douban_url varchar(150))'
                 );
}

function save_data($data_row) {
    $db = get_db();
    list($tname, $name, $owner, $ctime, $tags, $del, $field1, $field2, $doubanUrl) = $data_row;
    $ret = @sqlite_query($db, "INSERT INTO book_info VALUES ('$tname', '$name', '$owner', '$ctime', '$tags', '$del', '$field1', '$field2', '$doubanUrl')", $result_type, $err_msg);

    if (! $ret) {
        echo "输入数据($tname)失败: $err_msg !";
    } else {
        echo "0";
    }

    return $ret;
}

function dump_db() {
    $db = get_db();
    $result = @sqlite_query($db, 'select * from book_info', SQLITE_ASSOC);
    var_dump(sqlite_fetch_all($result));
}

function load_db_data($field_names, $type = SQLITE_NUM) {
    $db = get_db();
    $field_list_str = implode(",", $field_names);
    $result = sqlite_query($db, "select $field_list_str from book_info where book_deleted = 0", $type);
    return sqlite_fetch_all($result);
}

function set_deleted($target_name) {
    $db = get_db();
    $ret = @sqlite_query($db, "update book_info set book_deleted = 1 where target_name = '$target_name'");
    // $ret = @sqlite_query($db, "update book_info set book_deleted = 0");
    return $ret;
}

function update_book_info($tname, $book_name, $tags, $douban) {
    $db = get_db();
    $sql_str = "update book_info set book_name = '$book_name', book_tags = '$tags', douban_url = '$douban' where target_name = '$tname'";
    $ret = @sqlite_query($db, $sql_str);
    // $ret = @sqlite_query($db, "update book_info set book_deleted = 0");
    return $ret;
}

function load_book_info($target_name, $type = SQLITE_NUM) {
    $db = get_db();
    $result = sqlite_query($db, "select * from book_info where target_name = '$target_name'", $type);
    return sqlite_fetch_all($result);
}

# save_data(Array("112", "2", "3", "4", "5", "0006", "10", "8", "9"));
# dump_db();
# set_deleted("0");
# var_dump(load_db_data(Array("book_name", "book_deleted")));
