<?php
define("AddNewWhitelist", 0);
define("GetWhitelistNames", 1);
define("GetWhitelistContents", 2);
define("EditWhitelistContent", 3);

$retArray = array(0, "");

function dbUtil($type, $paramList) {
    global $retArray;
    try {
        $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
        $db = $mongo->exceptionFilter;

        if ($type == AddNewWhitelist) {
            return addNewWhitelist($db, $paramList);

        } elseif ($type == GetWhitelistNames) {
            return getWhitelistNames($db, $paramList);

        } elseif ($type == GetWhitelistContents) {
            return getWhitelistContents($db, $paramList);
        } elseif ($type == EditWhitelistContent) {
            return editWhitelistContent($db, $paramList);
        }

    } catch (MongoConnectionException $e) {
        $retArray = array("1", $e->getMessage());
    }
}
//paramList为白名单的name,status
function addNewWhitelist($db, $paramList) {
    $retArray=array();
    $collection = $db->whitelistIndex;
    $whitelistName = $paramList[0];
    $doc = $collection->findOne(array("whitelistName" => $whitelistName));
    if ($doc == null) {
        $collection->insert(array("whitelistName" => $whitelistName));
        $retArray = array(0, "白名单($whitelistName)添加成功!");
    } else {
        $retArray = array(1, "白名单($whitelistName)已经存在!");
    }
    return $retArray;
}

function getWhitelistNames($db, $paramList) {
    $retArray=array();
    $collection = $db->whitelistIndex;
    $cursor = $collection->find();
    foreach ($cursor as $doc) {
        array_push($retArray, $doc["whitelistName"]);
    }
    return $retArray;
}

function getWhitelistContents($db, $paramList) {
    $retArray = array();
    $whitelistName = $paramList[0];
   // echo "<hr/>in getWhitelistContents: $whitelistName";
    $collection = $db->whitelistInfo;
    $cursor = $collection->find(array("whitelistName" => $whitelistName));
    foreach ($cursor as $doc) {
        array_push($retArray, $doc["whitelistContent"]);
     //   echo "<hr/>cursor infos:".$doc["whitelistContent"];
    }
    return $retArray;
}
function editWhitelistContent($db, $paramList){
    $retArray = array();
    list($whitelistName,$op,$whitelistContent) = $paramList;
    echo "listname is:$whitelistName,op is $op,content is $whitelistContent ";
    $collection = $db->whitelistInfo;
    $doc = $collection->findOne(array("whitelistName" => $whitelistName, "whitelistContent" => $whitelistContent));
    if($op == "add"){
        if($doc == null){
            $collection->insert(array("whitelistName" => $whitelistName, "whitelistContent" => $whitelistContent));
            $retArray = array(0, "白名单($whitelistName,$whitelistContent)添加成功!");
        }else{
            $retArray = array(1, "白名单($whitelistName,$whitelistContent)已经存在!");
        }
    }elseif($op == "delete"){
        if($doc == null){
            $retArray = array(1, "白名单($whitelistName,$whitelistContent)不存在!");
        }else {
            $collection->remove(array("whitelistName" => $whitelistName, "whitelistContent" => $whitelistContent));
            $retArray = array(0, "白名单($whitelistName,$whitelistContent)删除成功!");
        }
        
    }
    return $retArray;
}

// 调试

$whitelistName = "4";
/*
$parm = array($whitelistName,"add","*fortest*");
echo "<hr/>$whitelistName";
echo "<hr/>";
$ret = dbUtil(EditWhitelistContent, $parm);
echo "add ret:$ret[1]<br/>";
echo "result:";
$ret2 = dbUtil(GetWhitelistContents, array($whitelistName));
foreach($ret2 as $a) {
    echo $a;
};
*/
/*
echo "<hr/>";
$parm = array($whitelistName,"delete","*fortest*");
$ret = dbUtil(EditWhitelistContent, $parm);
echo "delete ret:$ret[1]<br/>";
echo "result:";
$ret3 = dbUtil(GetWhitelistContents, array($whitelistName));
foreach($ret3 as $a) {
    echo $a;
};

*/

?>

