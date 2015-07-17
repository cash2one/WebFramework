<?php
define("HOST", "tb029x.corp.youdao.com");
define("USER", "yinxj");
define("PASSWORD", "^YHN6yhn");
define("DATABASE", "perfdata2");

$db = mysql_connect(HOST, USER, PASSWORD);
mysql_select_db(DATABASE, $db);

//get user infos
$datas = getAllDescData();
echo json_encode($datas);
mysql_close($db);

function getAllDescData()
{
    $alldata = array();
    $alldata["userinfo"] = getUserInfos();
    $alldata["machineinfo"] = getMachineInfos();
    return $alldata;
}

function getUserInfos(){
    global $db;
    $strSql1="SELECT ldap,product,type,cubetype,numvars,strvars,machinevars FROM indexTable";
    $result=mysql_query($strSql1,$db);
    $userinfos = array();
    while(list($userName, $product, $type, $cubetype, $numvar, $strvar, $machinevar) = mysql_fetch_array($result)) {
        if (!array_key_exists($userName, $userinfos)) {
            $userinfos[$userName] = array();
        }

        if (!array_key_exists($product, $userinfos[$userName])) {
            $userinfos[$userName][$product] = array();
        }

        $type_cube = $type."".$cubetype;
        if (!array_key_exists($type_cube, $userinfos[$userName][$product])) {
            $userinfos[$userName][$product][$type_cube] = array();
        }
        $numvar = trim( $numvar );
        $strvar = trim( $strvar );
        $machinevar = trim( $machinevar );
        
        $graphnum = array();
        if("" != $numvar){
            $graphnum = explode(",", $numvar);
        }
        $graphstr = array();
        if("" != $strvar){
            $graphstr = explode(",", $strvar);
        }
        $graphmachine = array();
        if("" != $machinevar){
            $graphmachine = explode(",", $machinevar);
        }
        $graph_name = array_merge($graphnum, $graphstr, $graphmachine);
        $userinfos[$userName][$product][$type_cube] = $graph_name;
    }
    //sort
    foreach($userinfos as $user => $userinfo){
        foreach($userinfo as $product => $keys){
            asort($userinfos[$user][$product]);
        }
        asort($userinfos[$user]);
    }
    asort($userinfos);
    return $userinfos;
}
function getMachineKeys(){
    global $db;
    $strsql = "select distinct keyName from machineKeys";
    $result = mysql_query( $strsql );
    $machinekeys = array();
    while( list($machinekey) = mysql_fetch_array($result)){
        $pos = strpos($machinekey,".");
        if(false != $pos){
            $machinekey = substr($machinekey,0,$pos);
        }
        if(!in_array($machinekey,$machinekeys)){
            array_push($machinekeys, $machinekey);
        }
    }
    sort($machinekeys);
    return $machinekeys;
}
function getMachines(){
    global $db;
    $strsql = "select distinct host from machines";
    $result = mysql_query( $strsql );
    $machines = array();
    while( list($machinename) = mysql_fetch_array($result)){
        $machinetype =  substr($machinename,0,2);
        if(!isSet($machines[$machinetype])){
            $machines[$machinetype] = array();
        }
        array_push( $machines[$machinetype], $machinename);
    }
    //sort machines
    foreach($machines as $type => $targetmachines){
        sort($machines[$type]);
    }
    asort($machines);
    return $machines;
}
function getMachineInfos(){
    $machineinfos = array();
    $machineinfos["machines"] = getMachines();
    $machineinfos["keys"] = getMachineKeys();
    return $machineinfos;
}
?>

