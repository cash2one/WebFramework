<?php

include(dirname(__FILE__) . "/../dbLib.php");

$prodName  = getValue("prodName");
$filePath  = getValue("filePath");

if ($filePath == "") {
    echo json_encode(array(
                "ret" => 1, 
                "msg" => "Error: FilePath is Empty !")
            );
    return;
}

function getValue($keyName) {
    global $_GET;
    if (array_key_exists($keyName, $_GET))
        return $_GET[$keyName];

    echo json_encode(array(
                "ret" => 1, 
                "msg" => "Error: Invalid Get Parameters!")
            );
    exit(1);
}

$codeCommentArr = dbUtil(LoadCodeCommentInfoTable, $prodName);
$retArr = dbUtil(GetStatus);
if ($retArr["ret"] == 1) {
    echo json_encode($retArr);
    return;
}

$retArr = array();
foreach ($codeCommentArr as $subArr) {
    if ($subArr["filePath"] != $filePath)
        continue;
    
    array_push($retArr, $subArr);
}

$retArr2 = array(
            "ret" => 0,
            "list" => $retArr
          );

echo json_encode($retArr2);
