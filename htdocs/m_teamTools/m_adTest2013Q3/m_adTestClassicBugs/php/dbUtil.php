<?php

define("SaveCategory", 0);
define("ReadCategory", 1);
define("SaveInputContent", 2);
define("ReadAllContents", 3);
define("ReadSpecificContent", 4);
define("UpdateSpecificContent", 5);

$retArray = array(0, "");

function dbUtil($type, $paramList = null) {
    global $retArray;
    try {
        $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
        $db = $mongo->adtestClassicBugs;

        if ($type == SaveCategory) {
            saveCategory($db, $paramList);

        } elseif ($type == ReadCategory) {
            return readCateList($db);

        } elseif ($type == SaveInputContent) {
            saveInputContent($db, $paramList);

        } elseif ($type == ReadAllContents) {
            return listContent($db);

        } elseif ($type == ReadSpecificContent) {
            return listContent2($db, $paramList);

        } elseif ($type == UpdateSpecificContent) {
            updateInfo($db, $paramList);
        }

    } catch (MongoConnectionException $e) {
        $retArray = array("1", $e->getMessage());
    }
}

function saveCategory($db, $paramList) {
    global $retArray;
    $cateName = trim($paramList[0]);
    $collection = $db->categories;
    $doc = $collection->findOne(array("cateName" => $cateName));
    if ($doc != null) {
        $retArray = array(1,"Error: cateName($cateName) already exists.");
    } else {
        $collection->insert(array("cateName" => $cateName));
        $retArray = array(0, "Info: save cateName($cateName) successfully.");
    }
}

function readCateList($db) {
    $retList = array();

    $collection = $db->categories;
    $cursor = $collection->find();
    foreach ($cursor as $doc) {
        array_push($retList, $doc["cateName"]);
    }

    sort($retList);
    return $retList;
}

function saveInputContent($db, $paramList) {
    global $retArray;
    list($user, $title, $desc, $cate, $corePoint) = $paramList;
    if (in_array("", $paramList) || $user == "选择用户") {
        $retArray = array(1, "错误：输入不完整!");
        return;
    }

    $collection = $db->bugDetail;
    $collection->insert(array("time" => time(), "user" => $user, "title" => $title, "desc" => $desc, "cateName" => $cate, "corePoint" => $corePoint, "deleted" => false));
    $retArray = array(0, "Info: save bug info successfully!");
}

function listContent($db) {
    $retList = array();
    
    $collection = $db->bugDetail;
    $cursor = $collection->find();
    foreach ($cursor as $doc) {
        array_push($retList, array($doc["_id"], date("Y-m-d", $doc["time"]), $doc["user"], $doc["title"], $doc["desc"], $doc["cateName"], $doc["corePoint"]));
    }

    return $retList;
}

function listContent2($db, $paramList) {
    $tempArr = array();
    $collection = $db->bugDetail;
    $doc = $collection->findOne(array("_id" => new MongoId($paramList[0])));
    array_push($tempArr, "<table border='1' name='" . $paramList[0] . "'>");
    array_push($tempArr, "<tr name='cateName'><th>分类: </th><td name='content'>" . $doc["cateName"] . "</td><td><a href='' name='edit'>编辑</a> <a href='' name='save'>保存</a></td></tr>");
    array_push($tempArr, "<tr name='user'><th>报告人: </th><td name='content'>" . $doc["user"] . "</td><td></td></tr>");
    array_push($tempArr, "<tr name='title'><th>标题: </th><td name='content'>" . $doc["title"] . "</td><td><a href='' name='edit'>编辑</a> <a href='' name='save'>保存</a></td></tr>");
    array_push($tempArr, "<tr name='desc'><th>描述: </th><td name='content'><pre>" . $doc["desc"] . "</pre></td><td><a href='' name='edit'>编辑</a> <a href='' name='save'>保存</a></td></tr>");
    array_push($tempArr, "<tr name='corePoint'><th>核心点: </th><td name='content'>" . $doc["corePoint"] . "</td><td><a href='' name='edit'>编辑</a> <a href='' name='save'>保存</a></td></tr>");
    array_push($tempArr, "</table>");

    return implode("\n", $tempArr);
}

function updateInfo($db, $paramList) {
    global $retArray;
    list($id, $fieldName, $value) = $paramList;
    if (in_array("", $paramList)) {
        $retArray = array(1, "错误：输入不完整!");
        return;
    }

    $collection = $db->bugDetail;
    $doc        = $collection->findOne(array("_id" => new MongoId($id)));
    if ($doc != null) {
        $collection->update(array("_id" => new MongoId($id)),   array('$set' => array($fieldName => $value)));
        $retArray = array(0, "Info: save bug info successfully!");
    } else {
        $retArray = array(1, "Error: 更新失败，主键($id)不存在!");
    }
}
