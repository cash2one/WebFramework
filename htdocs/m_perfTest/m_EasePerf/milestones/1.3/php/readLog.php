<?php date_default_timezone_set("PRC");
    
define("HOST", "tb029x.corp.youdao.com");
define("USER", "yinxj");
define("PASSWORD", "^YHN6yhn");
define("DATABASE", "perfdata2");
define("MAX_POINT_COUNT", 320);

$db = mysql_connect(HOST, USER, PASSWORD);
mysql_select_db(DATABASE, $db);


// read params from GET and package it as an array
function getQueryObject($param){
    if( get_magic_quotes_gpc() ){
        $queryobj = json_decode(stripslashes($param), true);
    } else {
        $queryobj = json_decode($param, true);
    }
    $queryobj2 = array(
        "userinfo" => array(
            "user"     => "yinxj",
            "product"  => "armani",
            "type_obj" => array(
                "searchtb037_ds" => array("search.process.time","cluster.time","search.state","search.process.state","cluster.status")
            )
        ),
        "machineinfo" => array(
            //"nb037" => array("cpu","mem")
        ),
        "from_ts"  => "1357706040",
        #"from_ts"  => "2012-08-24 19:19:40",
        #"end_ts"   => "2012-08-24 19:47:40"
        "end_ts"   => "1357709640"
    );
    return $queryobj;
}


$queryobj = getQueryObject($_GET["param"]);
#var_dump($queryobj);

//get detail query info
$results = getData( $queryobj );
// get results from mysql and unpack it as a string
echo json_encode($results);
#system("echo ".json_encode($results).">> 1");
mysql_close($db);
        
// ============ Function Area =================

function startsWith($targestring, $needle)
{
    return !strncmp($targestring, $needle, strlen($needle));
}

function endsWith($targestring, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($targestring, -$length) === $needle);
}
function getData( & $queryobj){
    $userqueryinfo = $queryobj["userinfo"];
    $machinequeryinfo = $queryobj["machineinfo"];
    $begin_ts = $queryobj["from_ts"];
    $end_ts = $queryobj["end_ts"];
    if( $begin_ts < 0){
        $end_ts = time();
        $begin_ts = $end_ts + $begin_ts;
    } else if( $begin_ts > $end_ts){
        $end_ts = time();
    }
    $alldata = array();
    if(0 !== sizeof($userqueryinfo)){
        $user = $userqueryinfo["user"];
        $selections = $userqueryinfo["selection"];
        foreach($selections as $product => $typeobj){
            $alldata[$product] = getUserData($user,$product,$typeobj,$begin_ts,$end_ts);
        }
    } 
    if(0 !==  sizeof($machinequeryinfo)){
        $alldata["_machineinfo_"] = getMachineData($machinequeryinfo,$begin_ts,$end_ts);
    }
    return $alldata;
}
function getMachineData($machineinfo,$begin_ts,$end_ts){
    $result = array();
    foreach($machineinfo as $clustername => $machineclusters){
        foreach($machineclusters as $machinename => $monitoritems){
            $machinedata = getMachineDataByName($machinename,$begin_ts,$end_ts,$monitoritems);
            if($machinedata){
                compressIDDatas($machinedata,$begin_ts,$end_ts);
                calcIDValues($machinedata);
                restrictIDUsersData($machinedata);
                $result[$machinename] = $machinedata;
            }
        }
    }
    return $result;
}
function getUserData($user,$product,$typeobj,$begin_ts,$end_ts){
    $result = array();
    if($typeobj){
        foreach ($typeobj as $type_cube => $selectedkeys) {
            //parse and get type ,cube
            list($type, $cube) = explode("", $type_cube);
            //calc id
            $id = md5($user . $product . $type . $cube);
            $iddata = getUserDataByID($id, $begin_ts, $end_ts, $selectedkeys);
            if($iddata){
                //attention: compress data or calcvalues in any order is ok;
                //bear in mind if compress first the process will be faster
                //while calcValue first,the values is much more precise
                compressIDDatas($iddata,$begin_ts,$end_ts);
                calcIDValues($iddata);
                restrictIDUsersData($iddata);
                $result[$type_cube] = $iddata;
            }
        }
    }
    return $result;
}

function restrictIDUsersData(& $iddata, $number = 4){
    $restrictkeyarray = array("users_cpu","users_mem","pinfo_otherusers.pcpu","pinfo_otherusers.pmem");
    foreach($restrictkeyarray as $restrictkey){
        if( isset($iddata[$restrictkey]) ){
            if( isset($iddata[$restrictkey]["_results"]) ){
                $userkeydatas = $iddata[$restrictkey]["_results"];
                $arraycount = count($userkeydatas) - $number;
                if( $arraycount > 0){
                    $tempuserarray = array();
                    foreach($userkeydatas as $user =>$userdata){
                        $tempuserarray[$user] = $userdata["avg"];
                    }
                    asort($tempuserarray);
                    foreach( $tempuserarray as $user =>$value ){
                        unset($iddata[$restrictkey][$user]);
                        unset($iddata[$restrictkey][$restrictkey][$user]);
                        $arraycount = $arraycount -1;
                        if($arraycount <= 0){
                            break;
                        }
                    }
                }
            }
        }
    }
    
}

/**
 * filter items
 * return values: type,key
 * type: undfined, number,string,process,other
 * 1 : number type
 * 2 : process type
 * 3 : other type
 * 4 : status type
 */
function getTypeAndFilter($curkey,$selectedkeys){
    $numberkeys = array("qps","avg","max","min","90","99");
    $processkeys = array("pcpu","pmem","pygc","pygct","pfgc","pfgct","pgct");
    $pos = strrpos($curkey,".");
    if(false != $pos){
        $keyhead = substr($curkey,0,$pos);
        $keytail = substr($curkey,$pos + 1);
        if( in_array($curkey,$selectedkeys) ){
            if(in_array($keytail,$processkeys)){
                return array("process",$keyhead,$keytail);
            } else{
                return array("other",$curkey,$keytail);
            }
        }else {
            if( in_array($keyhead,$selectedkeys) ){
                if(in_array($keytail,$numberkeys)){
                    return array("number",$keyhead,$keytail);
                }
            }
        }
        if(preg_match("/pinfo_user_.*.pcpu/",$curkey) && in_array("pinfo_otherusers.pcpu",$selectedkeys)){
            return array("other","pinfo_otherusers.pcpu",$curkey);
        } else if(preg_match("/pinfo_user_.*.pmem/",$curkey) && in_array("pinfo_otherusers.pmem",$selectedkeys)){
            return array("other","pinfo_otherusers.pmem",$curkey);
        }
    }
    $pos = strrpos($curkey,"^");
    if(false != $pos){
        $keyhead = substr($curkey,0,$pos);
        $keytail = substr($curkey,$pos);
        if( in_array($keyhead,$selectedkeys) ){
            return array("string",$keyhead,$keytail);
        }
    }

    return array("undefined","testtype","undefined");
}

/**
 * calc result and assume the id result is full result
 */
function calcIDValues(& $userresult,$floatprecise = 2){
    $calcresult = array();
    foreach ($userresult as $keyname => $values){
        $type = $values["type"];
        $calcresult[$keyname] = array();
        if("number" == $type){
            //numbers
            $qpskeyname = "qps"; #$keyname.".qps";
            if( isSet($values[$qpskeyname]) ){
                //calc qps first
                $sumqps = 0;
                $countqps = 0;
                $lasttimestamp = 0;
                $qpsresult = $values[$qpskeyname];
                $qpsmin = null;
                $qpsmax = null;
                foreach ($qpsresult as $timestamp => $qps){
                    if(null == $qps){
                        continue;
                    }
                    $sumqps += $qps;
                    $countqps += 1;
                    $lasttimestamp = $timestamp;
                    if($qpsmin > $qps || null === $qpsmin){
                        $qpsmin = $qps;
                    }
                    if($qpsmax < $qps || null === $qpsmax){
                        $qpsmax = $qps;
                    }
                }
                $calcresult[$keyname][$qpskeyname] = array();
                if ($countqps > 0){
                    $calcresult[$keyname][$qpskeyname]["avg"] = round($sumqps / $countqps,$floatprecise);
                } else {
                    $calcresult[$keyname][$qpskeyname]["avg"] = 0;
                }
                if(null === $qpsmin){
                    $qpsmin = 0;
                }
                if(null === $qpsmax){
                    $qpsmax = 0;
                }
                $calcresult[$keyname][$qpskeyname]["min"] = round($qpsmin,$floatprecise);
                $calcresult[$keyname][$qpskeyname]["max"] = round($qpsmax,$floatprecise);
                
                foreach ($values as $subkeyname => $subvalues){
                    if( ("type" == $subkeyname) || ($qpskeyname == $subkeyname) ){
                        continue;
                    }
                    $calcresult[$keyname][$subkeyname] = array();
                    $minvalue = null;
                    $maxvalue = null;
                    if($sumqps > 0){
                        $calcvalue = 0;                 
                        foreach ($subvalues as $timestamp => $value){
                            if(null == $value){
                                continue;
                            }
                            if($minvalue > $value || null === $minvalue){
                                $minvalue = $value;
                            }
                            if($maxvalue < $value || null === $maxvalue){
                                $maxvalue = $value;
                            }
                            $calcvalue += ( ($value * $qpsresult[$timestamp]) / $sumqps);
                        }
                        $calcresult[$keyname][$subkeyname]["avg"] = round($calcvalue,$floatprecise);
                    } else {
                        $calcresult[$keyname][$subkeyname]["avg"] = 0;
                    }
                    if(null === $minvalue){
                        $minvalue = 0;
                    }
                    if(null === $maxvalue){
                        $maxvalue = 0;
                    }
                    $calcresult[$keyname][$subkeyname]["min"] = round($minvalue,$floatprecise);
                    $calcresult[$keyname][$subkeyname]["max"] = round($maxvalue,$floatprecise);
                }
            }
        } else if( "string" == $type ){
            $qpsarray = array();
            $subsum = array();
            $sumall = 0;
            foreach ($values as $subkeyname => $subvalues){
                if("type" == $subkeyname){
                    continue;
                }
                $subsum[$subkeyname] = 0;
                foreach ($subvalues as $timestamp => $value){
                    if(null == $value){
                        continue;
                    }
                    if (!isSet($qpsarray[$timestamp])){
                        $qpsarray[$timestamp] = $value;
                    } else {
                        $qpsarray[$timestamp] += $value;
                    }
                    $subsum[$subkeyname] += $value;
                }
                $sumall += $subsum[$subkeyname];
            }
            $qpskeyname = "qps";
            $userresult[$keyname][$qpskeyname] = $qpsarray;
            $values = $userresult[$keyname];
            
            //each subkey update
            foreach ($values as $subkeyname => $subvalues){
                if("type" == $subkeyname){
                    continue;
                }
                $minvalue = null;
                $maxvalue = null;
                $avgvalue = 0;
                $timestampcount = 0;
                //update old value
                foreach ($subvalues as $timestamp => $value){
                    if(null == $value){
                        continue;
                    }
                    $timestampcount += 1;
                    $qpscurts = $qpsarray[$timestamp];
                    $newvalue = $value;
                    if($qpskeyname != $subkeyname) {
                        $newvalue = round($value / $qpscurts,$floatprecise);
                        $userresult[$keyname][$subkeyname][$timestamp] = $newvalue;
                    }
                    if($minvalue > $newvalue || null === $minvalue){
                        $minvalue = $newvalue;
                    }
                    if($maxvalue < $newvalue || null === $maxvalue){
                        $maxvalue = $newvalue;
                    }
                }
                $calcresult[$keyname][$subkeyname] = array();
                if($qpskeyname === $subkeyname) {
                    if($timestampcount > 0){
                        $avgvalue = $sumall / $timestampcount;
                    }
                } else {
                    if($sumall > 0){
                        $subsumvalue = $subsum[$subkeyname];
                        $avgvalue = $subsumvalue / $sumall;
                    }
                }
                if(null === $minvalue){
                    $minvalue = 0;
                }
                if(null === $maxvalue){
                    $maxvalue = 0;
                }
                $calcresult[$keyname][$subkeyname]["avg"] = round($avgvalue,$floatprecise);
                $calcresult[$keyname][$subkeyname]["min"] = round($minvalue,$floatprecise);
                $calcresult[$keyname][$subkeyname]["max"] = round($maxvalue,$floatprecise);               
            }           
        } else {
            // get average is ok
            foreach ($values as $subkeyname => $subvalues){
                if("type" == $subkeyname){
                    continue;
                }
                $sumvalue = 0;
                $countvalue = 0;
                $lasttimestamp = 0;
                $minvalue = null;
                $maxvalue = null;
                foreach ($subvalues as $timestamp => $value){
                    if(null == $value){
                        continue;
                    }
                    $sumvalue += $value;
                    $countvalue += 1;
                    $lasttimestamp = $timestamp;
                    if($minvalue > $value || null === $minvalue){
                        $minvalue = $value;
                    }
                    if($maxvalue < $value || null === $maxvalue){
                        $maxvalue = $value;
                    }
                }
                $calcresult[$keyname][$subkeyname] = array();
                if($countvalue){
                    $calcresult[$keyname][$subkeyname]["avg"] = round($sumvalue / $countvalue,$floatprecise);
                }else {
                    $calcresult[$keyname][$subkeyname]["avg"] = 0;
                }
                if(null === $minvalue){
                    $minvalue = 0;
                }
                if(null === $maxvalue){
                    $maxvalue = 0;
                }
                $calcresult[$keyname][$subkeyname]["min"] = round($minvalue,$floatprecise);
                $calcresult[$keyname][$subkeyname]["max"] = round($maxvalue,$floatprecise);
            }
        }
        //update value
        $userresult[$keyname]["_results"] = $calcresult[$keyname];
    }
}

//get min and max key,and size
function getMinMaxKeyAndSize($values){
    $minkey = null;
    $maxkey = null;
    $prekey = null;
    $mingap = null;
    $size = 0;
    foreach ($values as $tkey => $tvalue){
        #size
        $size = $size + 1;
        #min key
        if(null == $minkey){
            $minkey = $tkey;
        }else {
            if($tkey < $minkey){
                $minkey = $tkey;
            }
        }
        #max key
        if(null == $maxkey){
            $maxkey = $tkey;
        }else {
            if($tkey > $maxkey){
                $maxkey = $tkey;
            }
        }
        #mingap
        if(null != $prekey){
            $curgap = $tkey - $prekey;
            if(null == $mingap || $mingap > $curgap){
                $mingap = $curgap;
            }
        }
        $prekey = $tkey;
    }
    return array($minkey,$maxkey,$mingap,$size);
}
    
//compress data
function compressIDDatas(& $userresult,$begin_ts = null,$end_ts = null,$floatprecise = 4,$reservercount = 300,$ignorecount = 2){
    foreach ($userresult as $keyname => $values){
        foreach ($values as $subkeyname => $subvalues){
            if("type" == $subkeyname || "_results" == $subkeyname ){
                continue;
            }
            list($minkey,$maxkey,$tsgap,$valuecount) = getMinMaxKeyAndSize($subvalues);
            if($valuecount > 1 ){
                if(null != $begin_ts && null != $end_ts){
                    $calctsgap = ceil( ( $end_ts - $begin_ts ) / $reservercount );
                    if($calctsgap > $tsgap){
                        $tsgap = $calctsgap;
                    }
                }
                $ignoretsgap = $tsgap * ($ignorecount + 1);
                //init
                if(null != $begin_ts && $begin_ts < $minkey - $tsgap){
                    $compressdata[$begin_ts] = null;
                }
                if(null != $end_ts && $end_ts > $maxkey + 2 * $tsgap){
                    $compressdata[$end_ts] = null;
                }

                $startts = $minkey;
                $starttsmax = $startts + $tsgap;
                $compressdata = array();
                $count = 0;
                $sum = 0;
                foreach ($subvalues as $timestamp => $value){
                    if( $timestamp < $starttsmax ){
                        $count += 1;
                        $sum += $value;
                    } else {
                        if($count > 0){
                            $compressdata[$startts] = round($sum / $count,$floatprecise);
                        }
                        # if case just for data not total ok,will ingnore tag smaller then minn ignore gap
                        # if ignorecount del,and should use $compressdata[$startts]  = null;
                        if($timestamp - $startts > $ignoretsgap){
                            $compressdata[$startts] = null;
                        }
                        while( $starttsmax <= $timestamp){
                            $startts = (int) $starttsmax;
                            $starttsmax += $tsgap;
                            #$compressdata[$startts]  = null;
                        }
                        $count = 1;
                        $sum = $value;
                    }
                }
                if($count > 0){
                    $compressdata[$startts] = round($sum / $count,$floatprecise);
                }
                $userresult[$keyname][$subkeyname] = $compressdata;
            }
        }
    }
}

//get all key value of idx
function getUserDataByID($id,$begin_ts,$end_ts,$selectedkeys){  
    //get data from db
    $sql = sprintf("select time, keyName, keyValue from dataTable where indexID='%s' and time>=%s and time<=%s order by time", $id, $begin_ts, $end_ts);
    global $db;
    $dbresult = mysql_query($sql, $db) or die("Invalid query: " . mysql_error());
    $userresult = array();
    //parse data
    while (list($timeStamp, $keyName, $keyValue) = mysql_fetch_row($dbresult)){
        //filter the items. be ware that: the keyName is not exact the same as item
        list($type,$realKeyName,$sKeyName) = getTypeAndFilter($keyName,$selectedkeys);
        if ("undefined" != $type){
            if( !isSet($userresult[$realKeyName]) ){
                $userresult[$realKeyName] = array();
                $userresult[$realKeyName]["type"] = $type;
            }
            if(!isSet($userresult[$realKeyName][$sKeyName])){
                $userresult[$realKeyName][$sKeyName] = array();
            }
            $userresult[$realKeyName][$sKeyName][$timeStamp] = $keyValue;
        }
    }
    return $userresult;
}

function getMachineKeyType($keyName){
    $keytype = $keyName;
    $keyname = $keyName;
    $pos = strpos($keyName,".");
    if(false != $pos){
        $keytype = substr($keyName,0,$pos);
        $keyname = substr($keyName,$pos + 1); 
    }
    return array($keytype,$keyname);
}
function getMachineDataByName($machinename,$begin_ts, $end_ts, $selectedkeys){
    $sql = sprintf( "select time, keyName, keyValue from machineData where machineID = (select id from machines where host = '%s') and time>=%s and time<=%s order by time",$machinename,$begin_ts, $end_ts);
    global $db;
    $dbresult = mysql_query($sql, $db) or die("Invalid query: " . mysql_error());
    $machineresult = array();
    //parse data
    while (list($timeStamp, $keyName, $keyValue) = mysql_fetch_row($dbresult)){
        list($keytype,$keyName) = getMachineKeyType($keyName);
        if( in_array($keytype, $selectedkeys) ){
            if( !isSet($machineresult[$keytype]) ){
                $machineresult[$keytype] = array();
                $machineresult[$keytype]["type"] = "system";
            }
            if( !isSet( $machineresult[$keytype][$keyName])){
                $machineresult[$keytype][$keyName] = array();
            }
            $machineresult[$keytype][$keyName][$timeStamp] = $keyValue;
        }
    }
    return $machineresult;
}


?>

