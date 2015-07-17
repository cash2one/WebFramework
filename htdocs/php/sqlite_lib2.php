<?php

define("SQL_FILE_PATH2", "project_info.sqlite");

function get_db2() {
    $db_file_exists = file_exists(SQL_FILE_PATH2);
    $db = sqlite_open(SQL_FILE_PATH2, 0666, $sqliteerror);

    if (! $db)
        die($sqliteerror);
    if (! $db_file_exists)
        create_db2($db);

    return $db;
}

function create_db2($db) {
    sqlite_query($db, 'create table project_info (
                            id INTEGER PRIMARY KEY,
                            title varchar(200),
                            summary varchar(1500),
                            create_time varchar(30),
                            wiki varchar(100),
                            home varchar(100),
                            svn  varchar(100),
                            status int,
                            creator varchar(20),
                            members varchar(100),
                            extra_field1 varchar(100),
                            extra_field2 varchar(200))'
                 );
}

function add_edit_project($submitList, $type) {
    $db = get_db2();
    $new_time = time();
    list($id, $title, $summary, $wiki, $home, $svn, $status, $creator, $members) = $submitList;
    $title = htmlspecialchars($title, ENT_QUOTES);
    $summary = htmlspecialchars($summary, ENT_QUOTES);
    $sql_str = "";
    if ($type == 0) { //新建一个项目
        $sql_str = "insert into project_info VALUES (null,
                                                '$title',
                                                '$summary',
                                                '$new_time',
                                                '$wiki',
                                                '$home',
                                                '$svn',
                                                '$status',
                                                '$creator',
                                                '$members',
                                                '', '')";

    } elseif ($type == 1) { //编辑一个项目
        $sql_str = "update project_info set title = '$title',
                                            summary = '$summary',
                                            wiki = '$wiki',
                                            home = '$home',
                                            svn = '$svn',
                                            status = '$status',
                                            creator = '$creator',
                                            members = '$members' where id=$id";

    }
    
    $ret = @sqlite_query($db, $sql_str, $result_type, $err_msg);

    if (! $ret) {
        echo "更新数据库失败($sql_str): $err_msg !";
    } else {
        echo "0";
    }

    return $ret;
}

function dump_db2() {
    $db = get_db2();
    $result = @sqlite_query($db, 'select * from project_info', SQLITE_ASSOC);
    var_dump(sqlite_fetch_all($result));
}

function load_project($id, $type = SQLITE_NUM) {
    $db = get_db2();
    $result = sqlite_query($db, "select * from project_info where id='$id'", $type);
    return sqlite_fetch_all($result);
}

function load_all_projects($sort, $order, $type = SQLITE_NUM) {
    $db = get_db2();
    $result = sqlite_query($db, "select id, title, create_time, wiki, home, svn, status, creator, members from project_info where extra_field1 != 'del' order by $sort $order", $type);
    return sqlite_fetch_all($result);
}

function remove_project($id, $type = SQLITE_NUM) {
    $db = get_db2();
    $result = sqlite_query($db, "delete from project_info where id='$id'", $type);
    return sqlite_fetch_all($result);
}
