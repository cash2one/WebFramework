<?php

$_server_name2 = "nb403";
$_server_port2 = "27127";

function getCollection() {
    global $_server_name2, $_server_port2;
    $m = new MongoClient("$_server_name2:$_server_port2");
    $db = $m->selectDB("mysqlTableComment");
    return $db->fieldComment;
}

function getCommentInfo() {
    $retArray = array();
    $collect = getCollection();

    $cursor = $collect->find();
    foreach($cursor as $res) {
        $server_name = $res["server_name"];
        $server_port = $res["server_port"];
        $database    = $res["database"];
        $table_name  = $res["table_name"];

        $key = $server_name . ":" . $server_port . ":" . $database;
        if (! array_key_exists($key, $retArray)) {
            $retArray[$key] = array(); 
        }

        array_push($retArray[$key], $table_name);
    }
    return $retArray;
}

function get_comment_db($server_name, $server_port) {
    $retArray = array();
    $commentInfo = getCommentInfo();
    $keys = array_keys($commentInfo);
    foreach ($keys as $key) {
        list($server, $port, $db) = explode(":", $key);
        if ($server == $server_name && $port == $server_port) {
            array_push($retArray, $db);
        }
    }
    return $retArray;
}

function get_comment_table($server_name, $server_port, $db_name) {
    $commentInfo = getCommentInfo();
    $key = $server_name . ":" . $server_port . ":" . $db_name;
    if (array_key_exists($key, $commentInfo)) {
        return $commentInfo[$key];
    }
    return array();
}

function getTableComment($server_name, $server_port, $db_name, $table_name) {
    $retArray = array();
    $collect = getCollection();

    $cond_array = array(
        "server_name" => $server_name,
        "server_port" => $server_port,
        "database"    => $db_name,
        "table_name"  => $table_name,
    );

    $cursor = $collect->find($cond_array);
    foreach($cursor as $res) {
        print_r($res);
        $fieldName = $res["field_name"];
        $chineseName = $res["chinese_name"];
        $commentStr = $res["comment_str"];
        $retArray[$fieldName] = array($chineseName, $commentStr);
    }
    return $retArray;
}

/*
$mysql_field_content = array(
    "fieldName" => "SPONSOR_ID",
    "chineseName" => "广告商ID2",
    "commentList"    => array(
        "1" => "sponsor_id_1",
        "2" => "sponsor_id_2",
    ),
);
print_r(getTableComment("tb081", "3306", "eadb3", "SPONSOR_MONTHLY_SETTLEMENT"));
*/

function saveComment($server_name, $server_port, $db_name, $table_name, $mysql_field_content_list) {
    $retArray = array();
    $collect = getCollection();

    foreach ($mysql_field_content_list as $mysql_field_content) {
        $cond_array = array(
            "server_name" => $server_name,
            "server_port" => $server_port,
            "database"    => $db_name,
            "table_name"  => $table_name,
            "field_name"  => $mysql_field_content["fieldName"]
        );

        $val_array = array(
            "server_name" => $server_name,
            "server_port" => $server_port,
            "database"    => $db_name,
            "table_name"  => $table_name,
            "field_name"  => $mysql_field_content["fieldName"],
            "chinese_name" => $mysql_field_content["chineseName"],
            "comment_str" => $mysql_field_content["commentStr"]
        );

        $result = $collect->update($cond_array, $val_array, array("upsert" => true));
    }
}

?>
