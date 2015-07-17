<?php
require_once("../php/dbUtil.php");

function getListNames() {
    $whitelistNames = dbUtil(GetWhitelistNames, null);
    sort($whitelistNames);
    return $whitelistNames;
}

function getListContentsByName($whitelistName){
    $whitelistContents = dbUtil(GetWhitelistContents, array($whitelistName));
    sort($whitelistContents);
    return $whitelistContents;
}

function addListName($whitelistName){
    $sta = dbUtil(AddNewWhitelist,array($whitelistName));
    return $sta;
}

function deleteListContent($whitelistName,$whitelistContent){
    $ret = dbUtil(EditWhitelistContent,array($whitelistName,"delete",$whitelistContent));
    return $ret;
}

function addListContent($whitelistName,$whitelistContent){
    $ret = dbUtil(EditWhitelistContent,array($whitelistName,"add",$whitelistContent));
    return $ret;
}    

/*
$whitelistName = "5";
echo "<hr/>add test-1";
$ret1 = addListContent($whitelistName,"test-1");
echo "<br/>$ret1[0]";
$ret = getListContentsByName($whitelistName);
foreach($ret as $a){
  echo "<br/>$a";
};
*/
/*
echo "<hr/>delete test-1";
$ret1 = deleteListContent($whitelistName,"test-1");
echo "<br/>$ret1";
$ret = getListContentsByName($whitelistName);
foreach($ret as $a){
echo "<br/>$a";
};
*/
?>
