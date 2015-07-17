<?php

define("AddNewVersion", 0);
define("LoadVersions", 1);
define("SaveRunInfo", 2);

$retArray = array(0, "");

function dbUtil($type, $paramList = null) {
    global $retArray;
    try {
        $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
        $db = $mongo->onlineRegressionMonitor;

        if ($type == AddNewVersion) {
            addNewVersion($db, $paramList);

        } elseif ($type == LoadVersions) {
            return loadVersions($db, $paramList);

        } elseif ($type == SaveRunInfo) {
            saveRunInfo($db, $paramList);
        }

    } catch (MongoConnectionException $e) {
        $retArray = array("1", $e->getMessage());
    }
}

function addNewVersion($db, $paramList) {
    global $retArray;
    list($prodName, $verStr) = $paramList;
    $collection = $db->prodVersionDetail;
    $doc = $collection->findOne(array("prodName" => $prodName, "version" => $verStr));
    if ($doc == null) {
        $collection->insert(array("prodName" => $prodName, "version" => "$verStr", "tsList" => array()));
        $retArray = array(0, "产品($prodName)的版本名($verStr)添加成功!");
    } else {
        $retArray = array(1, "产品($prodName)的版本名($verStr)已经存在!");
    }
}

function loadVersions($db, $paramList) {
    $retArray = array();
    $prodName = $paramList[0];
    $collection = $db->prodVersionDetail;
    $cursor = $collection->find(array("prodName" => $prodName));
    foreach ($cursor as $doc) {
        array_push($retArray, $doc["version"]);
    }
    return $retArray;
}

function saveRunInfo($db, $paramList) {
    $retArray = array();
    list($prodName, $version, $timestamp) = $paramList;
    $collection = $db->prodVersionDetail;
    $collection->update(array("prodName" => $prodName, "version" => $version), array('$addToSet' => array("tsList" => $timestamp)));
}
