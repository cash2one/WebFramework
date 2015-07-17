<?php

header("Content-type: text/html; charset=utf-8");

// === 全局变量定义区
$status_arr = array(
    "ret" => 0, 
    "msg" => ""
);

define("LoadCodePathInfoTable", 0);
define("LoadProdTableNameMapInfoTable", 1);
define("LoadCodeCommentInfoTable", 2);
define("SaveCodePathInfo", 3);
define("SaveProdTableNameMapInfo", 4);
define("SaveCodeCommentInfo", 5);
define("CreateCollection", 6);
define("SetStatus", 21);
define("GetStatus", 22);

// == 状态函数定义区
function reset_db_result() {
    global $status_arr;
    $status_arr = array(
        "ret" => 0, 
        "msg" => ""
    );
}

function set_db_result($status, $msg) {
    global $status_arr;
    $status_arr = array(
        "ret" => $status, 
        "msg" => $msg 
    );
    return true;
}

function get_db_result() {
    global $status_arr;
    return $status_arr;
}

// == 公共api
function load_table($db, $tableName) {
    $collectionNames = $db->getCollectionNames();
    if (! in_array($tableName, $collectionNames)) {
        set_db_result(1, "Error: 数据库中没有该集合($tableName)!");
        return array();
    }

    $retArray = array();
    $collection = $db->selectCollection($tableName);
    $cursor = $collection->find();
    foreach ($cursor as $doc) {
        array_push($retArray, $doc);
    }
    return $retArray;
}

function create_table($db, $tableName) {
    $collectionNames = $db->getCollectionNames();
    if (in_array($tableName, $collectionNames)) {
        set_db_result(1, "错误：数据库中的表($tableName)已存在!");
        return false;
    }   

    $obj = preg_match("/^\w+$/", $tableName);
    if ($obj == 0) {
        set_db_result(1, "错误：表名只能是字符或字母下划线组成!");
        return false;
    }
             
    if (strlen($tableName) > 20 || strlen($tableName) < 4) {
        set_db_result(1, "错误：表名长度不能大于20个字符且小于4个字符");
        return false;
    }   
                 
    $db->createCollection($tableName);
    return true;
}

// == mongodb api函数定义区
function load_codePathInfoTable($db) {
    return load_table($db, "codePathInfo");
}

function load_prodTableNameMapInfoTable($db) {
    $retArray = array();

    $retArr = load_table($db, "prodTableNameMapInfo");
    foreach ($retArr as $arr) {
        $prodName = $arr["prodName"];
        $tableName = $arr["tableName"];
        $retArray[$prodName] = $tableName;
    }

    return $retArray;
}

function load_codeCommentInfoTable($db, $prodName) {
    $tableArr = load_prodTableNameMapInfoTable($db);

    if (! array_key_exists($prodName, $tableArr)) {
        set_db_result(1, "错误：产品$prodName 没有对应的表, 请在配置页面中创建!");
        return array();
    }

    $tableName = $tableArr[$prodName];
    return load_table($db, $tableName);
}

function save_codePathInfo($db, $svnAddr, $version, $prodName, $creator) {
    $tableArr = load_codePathInfoTable($db);
    foreach ($tableArr as $arr) {
        if ($arr["svnPath"] == $svnAddr) {
            if ($prodName != $arr["prodName"]) {
                set_db_result(1, "错误: svn($svnAddr)已经存在于表codePathInfo中!");
            } else {
                set_db_result(2, "错误: svn($svnAddr VS $prodName)已经存在于表codePathInfo中!");
            }
            return false;
        }
    }

    $collection = $db->codePathInfo;
    $collection->insert(array("svnPath" => $svnAddr, "version" => $version, "prodName" => $prodName, "creator" => $creator, "time" => time()));
    set_db_result(0, "提示: 保存($svnAddr)成功!");
    return true;
}

function save_prodTableNameMapInfo($db, $prodName, $tableName) {
    if (strlen($tableName) < 3 || strlen($tableName) > 30) {
        set_db_result(1, "错误：表名($tableName)的长度不能少于3或者大于30 !");
        return false;
    }

    if (preg_match("/^\w+$/", $tableName, $matches) == 0) {
        set_db_result(1, "错误：表('$tableName')名只能由数字、字母或者下划线构成!");
        return false;
    }

    $tableArr = load_prodTableNameMapInfoTable($db);
    if (array_key_exists($prodName, $tableArr)) {
        set_db_result(1, "错误：产品$prodName 已有对应的表!");
        return false;
    }

    if (in_array($tableName, $tableArr)) {
        set_db_result(1, "错误：表名$tableName已被使用!");
        return false;
    }

    $collectionNames = $db->getCollectionNames();
    if (in_array($tableName, $collectionNames)) {
        set_db_result(1, "错误: 该表名($tableName)已存在!");
        return false;
    }

    $collection = $db->prodTableNameMapInfo;
    $collection->insert(array("prodName" => $prodName, "tableName" => $tableName));

    return create_table($db, $tableName);
}

function save_codeCommentInfo($db, $prodName, $version, $status, $filePath, $commentor, $type, $content, $detailType, $select_id) {
    $tableArr = load_prodTableNameMapInfoTable($db);
    if (! array_key_exists($prodName, $tableArr)) {
        set_db_result(1, "错误：产品$prodName 无对应的表!");
        return false;
    }

    $tableName = $tableArr[$prodName];
    $collection = $db->selectCollection($tableName);
    if ($select_id == -1) {
        // 插入状态
        $doc = $collection->findOne(array("version" => $version, "filePath" => $filePath, "type" => $type, "detailType" => $detailType));
        if ($doc != null) {
            set_db_result(1, "错误：$type:$detailType 已经存在!");
            return false;
        }

        $collection->insert(
            array("version" => $version, "filePath" => $filePath, "type" => $type, "detailType" => $detailType, 
                    "commentors" => array($commentor), "status" => $status, "content" => $content, "time" => time())
        );
    } else {
        $doc = $collection->findOne(array("version" => $version, "filePath" => $filePath, "type" => $type, "detailType" => $detailType));
        if ($doc != null && $doc["_id"] != $select_id) {
            set_db_result(1, "错误：$type:$detailType 已经存在!");
            return false;
        }

        $collection->update(
            array("_id" => new MongoId($select_id)),
            array(
                '$addToSet' => array("commentors" => $commentor), 
                '$set' => array("version" => $version, "filePath" => $filePath, "type" => $type, "detailType" => $detailType, 
                                "status" => $status, "content" => $content, "time" => time())
                )
            //array('upsert' => true)
        );
    }
    set_db_result(0, "提示：产品$prodName@$version 的注释保存成功 !");
    backup_codeCommentInfo($db, $prodName, $tableName, $version, $filePath, $commentor, $type, $content, $detailType);
}

function backup_codeCommentInfo($db, $prodName, $tableName, $version, $filePath, $commentor, $type, $content, $detailType) {
    $collection = $db->codeCommentBackup;
    $collection->insert(array("prodName" => $prodName, "tableName" => $tableName, "version" => $version, "filePath" => $filePath, "type" => $type, "detailType" => $detailType, "commentor" => $commentor, "content" => $content, "time" => time()));
}

// === API Interface
function dbUtil() {

    $args_arr = func_get_args();
    $type = $args_arr[0];
    if ($type != GetStatus && $type != SetStatus)
        reset_db_result();

    try {
        $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
        $db = $mongo->svnCodeComment;

        if ($type == LoadCodePathInfoTable) {
            return load_codePathInfoTable($db);

        } elseif ($type == LoadProdTableNameMapInfoTable) {
            return load_prodTableNameMapInfoTable($db);

        } elseif ($type == LoadCodeCommentInfoTable) {
            $prodName = $args_arr[1];
            return load_codeCommentInfoTable($db, $prodName);

        } elseif ($type == SaveCodePathInfo) {
            list($svnAddr, $version, $prodName, $creator) = array_slice($args_arr, 1);
            return save_codePathInfo($db, $svnAddr, $version, $prodName, $creator);

        } elseif ($type == SaveProdTableNameMapInfo ) {
            list($prodName, $tableName) = array_slice($args_arr, 1);
            return save_prodTableNameMapInfo($db, $prodName, $tableName);

        } elseif ($type == SaveCodeCommentInfo) {
            list($prodName, $version, $status, $filePath, $commentor, $type, $content, $detail_type, $selected_id) = array_slice($args_arr, 1);
            return save_codeCommentInfo($db, $prodName, $version, $status, $filePath, $commentor, $type, $content, $detail_type, $selected_id);

        } elseif ($type == SetStatus) {
            list($status, $msg) = array_slice($args_arr, 1);
            return set_db_result($status, $msg);

        } elseif ($type == GetStatus) {
            return get_db_result();

        } elseif ($type == CreateCollection) {
            $tableName = $args_arr[1];
            return create_table($db, $tableName);
        }

    } catch (MongoConnectionException $e) {
        set_db_result(1, $e->getMessage());
        return false;
    }
}
