<?php

define("GetProductList", 0);
define("GetVersionList", 1);
define("CodeSearch", 2);
define("CodeSearchDetail", 3);
define("AddCodeInfo", 4);
define("GetCommentorList", 5);
define("GetCreator", 6);
define("GetCodeDiskPath", 7);
define("GetCodeInfo", 8);
define("GetProdNameMap", 9);
define("SetProdNameMap", 10);

$db_status_arr = array(0, "");

function reset_db_result() {
    global $db_status_arr;
    $db_status_arr = array(0, "");
}

function set_db_result($status, $msg) {
    global $db_status_arr;
    $db_status_arr = array($status, $msg); 
}

function get_db_result() {
    global $db_status_arr;
    return $db_status_arr;
}

function dbUtil() {
    reset_db_result();
    $args_arr = func_get_args();
    $type = $args_arr[0];

    try {
        $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
        $db = $mongo->svnCodeComment;

        if ($type == GetProductList) {
            return getProductList($db);

        } elseif ($type == GetVersionList) {
            $prodName = $args_arr[1];
            return getVersionList($db, $prodName);

        } elseif ($type == CodeSearch) {
            list($prodName, $version, $keyword) = array_slice($args_arr, 1);
            return codeSearch($db, $prodName, $version, $keyword);

        } elseif ($type == CodeSearchDetail) {
            list($prodName, $id) = array_slice($args_arr, 1);
            return codeSearchDetail($db, $prodName, $id);

        } elseif ($type == AddCodeInfo) {
            list($codeSvnPathWithVersion, $version, $prodName, $creator, $diskPath) = array_slice($args_arr, 1);
            return addCodeInfo($db, $codeSvnPathWithVersion, $version, $prodName, $creator, $diskPath);

        } elseif ($type == GetCommentorList) {
            $prodName = $args_arr[1];
            return getCommentorList($db, $prodName);

        } elseif ($type == GetCreator) {
            list($prodName, $version) = array_slice($args_arr, 1);
            return getCreator($db, $prodName, $version);

        } elseif ($type == GetCodeDiskPath) {
            list($prodName, $version) = array_slice($args_arr, 1);
            return getCodeDiskPath($db, $prodName, $version);

        } elseif ($type == GetCodeInfo) {
            list($prodName, $version) = array_slice($args_arr, 1);
            return getCodeInfo($db, $prodName, $version);

        } elseif ($type == GetProdNameMap) {
            return getProdNameMap($db);

        } elseif ($type == SetProdNameMap) {
            list($prodName, $tableName) = array_slice($args_arr, 1);
            return setProdNameMap($db, $prodName, $tableName);
        }

    } catch (MongoConnectionException $e) {
        set_db_result("1", $e->getMessage());
    }
}

function getProductList($db) {
    $retArray = array();
    $collection = $db->codePathInfo;
    $cursor = $collection->find();
    foreach ($cursor as $doc) {
        array_push($retArray, $doc["prodName"]);
    }
    $retArray = array_unique($retArray);
    set_db_result(1, "Info: get product list successfully.");
    return $retArray;
}

function getVersionList($db, $prodName) {
    $retArray = array();
    $collection = $db->codePathInfo;
    $cursor = $collection->find(array("prodName" => $prodName));
    foreach ($cursor as $doc) {
        array_push($retArray, $doc["version"]);
    }
    $retArray = array_unique($retArray);
    set_db_result(1, "Info: get version list successfully.");
    return $retArray;
}

function codeSearch($db, $prodName, $version, $search_str, $idx = 0, $length = 10) {
    $retArray = array();
    $nameMap = dbUtil(GetProdNameMap);
    if (! array_key_exists($prodName, $nameMap)) {
        set_db_result(1, "Error: Can't find prodName($prodName) in collection prodTableNameMap.");
    }
    $tableName = $nameMap[$prodName];

    $collectionNames = $db->getCollectionNames();
    if (! in_array($tableName, $collectionNames)) {
        set_db_result(1, "Error: Can't find collection($tableName) in db");
        return $retArray;
    }

    $collection2 = $db->selectCollection($tableName);
    $cursor = null;
    if ($version == "*") {
        $cursor = $collection2->find(array("content" => new MongoRegex('/' . $search_str . '/$i')));
    } else {
        $cursor = $collection2->find(array("version" => $version, "content" => new MongoRegex('/' . $search_str . '/$i')));
    }
    foreach ($cursor as $doc) {
        array_push($retArray, $doc);
    }
    set_db_result(0, "Info: Search info successfully.");
    return array_slice($retArray, $idx, $length);
}

function codeSearchDetail($db, $prodName, $id) {
    $retArray = array();
    $nameMap = dbUtil(GetProdNameMap);
    if (! array_key_exists($prodName, $nameMap)) {
        set_db_result(1, "Error: Can't find prodName($prodName) in collection prodTableNameMap.");
    }
    $tableName = $nameMap[$prodName];

    $collectionNames = $db->getCollectionNames();
    if (! in_array($tableName, $collectionNames)) {
        set_db_result(1, "Error: Can't find collection($tableName) in db");
        return $retArray;
    }

    $collection2 = $db->selectCollection($tableName);
    $retArray = $collection2->findOne(array("id" =>  new MongoId($id)));
    set_db_result(0, "Info: Search detail info successfully.");
    return $retArray;
}

function addCodeInfo($db, $codeSvnPathWithVersion, $version, $prodName, $creator, $diskPath) {
    $collection = $db->codePathInfo;
    $doc = $collection->findOne(array("codeSvnPath" => $codeSvnPathWithVersion, "prodName" => $prodName));
    if ($doc == null) {
        $collection->insert(array("codeSvnPath" => $codeSvnPathWithVersion, "version" => $version, "prodName" => $prodName, "creator" => $creator, "diskPath" => $diskPath, "time" => time()));
        set_db_result(0, "svn($codeSvnPathWithVersion)添加成功!");
    } else {
        set_db_result(1, "svn($codeSvnPathWithVersion)已存在, 添加失败!");
    }
}

function getCommentorList($db, $prodName) {
    $retArray = array();
    $collection = $db->prodTableNameMap;
    $doc = $collection->findOne(array("prodName" => $prodName));
    if ($doc == null) {
        set_db_result(1, "Error: Can't find prodName($prodName) in collection prodTableNameMap.");
        return $retArray;
    }
    $tableName = $doc["tableName"];

    $collectionNames = $db->getCollectionNames();
    if (! in_array($tableName, $collectionNames)) {
        set_db_result(1, "Error: Can't find collection($tableName) in db");
        return $retArray;
    }

    $collection2 = $db->selectCollection($tableName);
    $cursor = $collection2->find();
    foreach ($cursor as $doc) {
        foreach ($doc["commentors"] as $commentor) {
            array_push($retArray, $commentor);
        }
    }
    array_unique($retArray);
    set_db_result(0, "Info: find commentor list successfully.");
    return $retArray;
}

function getCreator($db, $prodName, $version) {
    $codeInfo = dbUtil(GetCodeInfo, $prodName, $version);
    if ($codeInfo == null) {
        set_db_result(1, "Error: can't find creator with prod($prodName) + version($version)");
        return $codeInfo;
    } else {
        set_db_result(0, "Info: find creator with prod($prodName) + version($version)");
        return $codeInfo["creator"];
    }
}

function getCodeDiskPath($db, $prodName, $version) {
    $codeInfo = dbUtil(GetCodeInfo, $prodName, $version);
    if ($codeInfo == null) {
        set_db_result(1, "Error: can't find code diskPath with prod($prodName) + version($version)");
        return $codeInfo;
    } else {
        set_db_result(0, "Info: find code diskPath with prod($prodName) + version($version)");
        return $codeInfo["diskPath"];
    }
}

function getCodeInfo($db, $prodName, $version) {
    $retArray = array();
    $collection = $db->codePathInfo;
    $doc = $collection->findOne(array("prodName" => $prodName, "version" => $version));
    if ($doc == null) {
        set_db_result(1, "Error: can't find codeinfo with prod($prodName) + version($version)");
        return null;
    } else {
        set_db_result(0, "Info: find codeinfo with prod($prodName) + version($version)");
        return $doc;
    }
}

function getProdNameMap($db) {
    $retArray = array();
    $collection = $db->prodTableNameMap;
    $cursor = $collection->find();
    foreach ($cursor as $doc) {
        $prodName = $doc["prodName"];
        $tableName = $doc["tableName"];
        $retArray[$prodName] = $tableName;
    }
    set_db_result(0, "Info: get prodname and tablename pair list successfully");
    return $retArray;
}

function setProdNameMap($db, $prodName, $tableName) {
    $prodNameMap = dbUtil(GetProdNameMap);
    if (array_key_exists($prodName, $prodNameMap)) {
        set_db_result(1, "错误：产品名($prodName)已存在!");
        return;
    }

    if (in_array($tableName, $prodNameMap)) {
        set_db_result(1, "错误：表名($tableName)已存在!");
        return;
    }
    
    $collectionNames = $db->getCollectionNames();
    if (in_array($tableName, $collectionNames)) {
        set_db_result(1, "错误：数据库中的表($tableName)已存在!");
    }

    $obj = preg_match("/^\w+$/", $tableName);
    if ($obj == 0) {
        set_db_result(1, "错误：表名只能是字符或字母下划线组成!");
        return;
    }

    if (strlen($tableName) > 20) {
        set_db_result(1, "错误：表名长度不能大于20个字符");
        return;
    }

    $db->createCollection($tableName);

    $collection = $db->prodTableNameMap;
    $collection->insert(array("prodName" => $prodName, "tableName" => $tableName));
    set_db_result(0, "提示：产品名($prodName)与表名($tableName)映射成功!");
}
