<?php date_default_timezone_set("PRC");
    
define("HOST", "tb029x.corp.youdao.com");
define("USER", "yinxj");
define("PASSWORD", "^YHN6yhn");
define("DATABASE", "perfdata");
define("MAX_POINT_COUNT", 320);

$db = mysql_connect(HOST, USER, PASSWORD);
mysql_select_db(DATABASE, $db);


// read params from GET and package it as an array
function getQueryObject($param){
    $qryObj = json_decode(stripslashes($param), true);
    $qryObj2 = array(
        "user"     => "yinxj",
        "product"  => "armani",
        "type_obj" => array(
            "searchtb037_ds" => array("search.process.time","cluster.time","search.state","search.process.state","cluster.status")
        ),
        "from_ts"  => "1357706040",
        #"from_ts"  => "2012-08-24 19:19:40",
        #"end_ts"   => "2012-08-24 19:47:40"
        "end_ts"   => "1357709640"
    );
    return $qryObj;
}



$qryObj = getQueryObject($_GET["param"]);
#var_dump($qryObj);

//get detail query info
$user = $qryObj["user"];
$product = $qryObj["product"];
$typeobj = $qryObj["type_obj"];
$begin_ts = $qryObj["from_ts"];
$end_ts = $qryObj["end_ts"];


$results = getAllData($user,$product,$typeobj,$begin_ts,$end_ts);
// get results from mysql and unpack it as a string
echo json_encode($results);
#system("echo ".json_encode($results).">> 1");
mysql_close($db);
        
// ============ Function Area =================

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function getAllData($user,$product,$typeobj,$begin_ts,$end_ts){
    $result = array();
    foreach ($typeobj as $type_cube => $selectedkeys) {
        //parse and get type ,cube
        list($type, $cube) = explode("", $type_cube);
        //calc id
        $id = md5($user . $product . $type . $cube);
        $iddata = getDataByID($id, $begin_ts, $end_ts, $selectedkeys);
        if($iddata){
            //attention: compress data or calcvalues in any order is ok;
            //bear in mind if compress first the process will be faster
            //while calcValue first,the values is much more precise
            compressDatas($iddata,$begin_ts,$end_ts);
            calcValues($iddata);
            $result[$type_cube] = $iddata;
        }
    }
    return $result;
}

/**
 * filter items
 * return values: type,key
 * type: undfined, number,string,process,system
 * 1 : number type
 * 2 : process type
 * 3 : sys type
 * 4 : status type
 */
function filterJudgeType($curkey,$selectedkeys){
    $numberkeys = array(".qps",".avg",".max",".min",".90",".99");
    $processkeys = array(".pcpu",".pmem");
    $syskeys = array(".sswap_in",".sswap_out",".sblock_in",".sblock_out",".ssys_in",".ssys_cs",".ssys_load",".scpu_us",".scpu_sy",".scpu_id",".scpu_wa",".smem_total",".smem_used",".smem_buffer",".smem_cache");
    $pos = strrpos($curkey,".");
    if(false != $pos){
        $keyhead = substr($curkey,0,$pos);
        $keytail = substr($curkey,$pos);
        if( in_array($curkey,$selectedkeys) ){
            if(in_array($keytail,$processkeys)){
                return array("process",$curkey);
            } else if(in_array($keytail, $syskeys)){
                return array("system",$curkey);
            }
        }else {
            if( in_array($keyhead,$selectedkeys) ){
                if(in_array($keytail,$numberkeys)){
                    return array("number",$keyhead);
                }
            }
        }
    }
    $pos = strrpos($curkey,"^");
    if(false != $pos){
        $keyhead = substr($curkey,0,$pos);
        if( in_array($keyhead,$selectedkeys) ){
            return array("string",$keyhead);
        }
    }

    return array("undefined","testtype");
}

/**
 * calc result and assume the id result is full result
 */
function calcValues(& $idresult){
    $floatprecise = 4;
    $calcresult = array();
    foreach ($idresult as $keyname => $values){
        $type = $values["type"];
        $calcresult[$keyname] = array();
        if("number" == $type){
            //numbers
            $qpskeyname = $keyname.".qps";
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
                    if( ("type" == $subkeyname) || ($qpskeynam == $subkeyname) ){
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
            $qpskeyname = $keyname.".qps";
            $idresult[$keyname][$qpskeyname] = $qpsarray;
            $values = $idresult[$keyname];
            
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
                        $idresult[$keyname][$subkeyname][$timestamp] = $newvalue;
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
        $idresult[$keyname]["_results"] = $calcresult[$keyname];
    }
}

//get min and max key
function getMinMaxKey($values){
    $minkey = null;
    $maxkey = null;
    foreach ($values as $tkey => $tvalue){
        if(null == $minkey){
            $minkey = $tkey;
        }else {
            if($tkey < $minkey){
                $minkey = $tkey;
            }
        }
        if(null == $maxkey){
            $maxkey = $tkey;
        }else {
            if($tkey > $maxkey){
                $maxkey = $tkey;
            }
        }
    }
    return array($minkey,$maxkey);
}
    

//compress data
function compressDatas(& $idresult,$begin_ts = null,$end_ts = null,$reservercount = 300){
    $floatprecise = 4;
    foreach ($idresult as $keyname => $values){
        foreach ($values as $subkeyname => $subvalues){
            if("type" == $subkeyname || "_results" == $subkeyname ){
                continue;
            }

            $minkey = $begin_ts;
            $maxkey = $end_ts;
            if(null == $begin_ts || null == $end_ts){
                list($minkey,$maxkey) = getMinMaxKey($subvalues);    
            } else {
            }
            if(null != $minkey && null != $maxkey){
                $tsgap = ( $maxkey - $minkey ) / $reservercount;
                if($tsgap > 30){    //if tsgap small than 30 do nothing
                    //init
                    $compressdata[$minkey] = null;
                    $compressdata[$maxkey] = null;

                    $startts = $minkey;
                    $starttsmax = $startts + $tsgap;
                    $compressdata = array();
                    $count = 0;
                    $sum = 0;
                    foreach ($subvalues as $timestamp => $value){
                        if( $timestamp <= $starttsmax ){
                            $count += 1;
                            $sum += $value;
                        } else {
                            if($count > 0){
                                $compressdata[$startts] = round($sum / $count,$floatprecise);
                            } else {
                                $compressdata[$startts] = null;
                            }
                            while( $starttsmax < $timestamp){
                                $startts = (int) $starttsmax;
                                $starttsmax += $tsgap;
                                $compressdata[$startts]  = null;
                            }
                            $count = 1;
                            $sum = $value;
                        }                            
                    }
                    $idresult[$keyname][$subkeyname] = $compressdata;
                }
            }
        }
    }
}

//get all key value of idx
function getDataByID($id,$begin_ts,$end_ts,$selectedkeys){  
    //get data from db
    $sql = sprintf("select t, keyName, keyValue from dataTable where id1='%s' and t>=%s and t<=%s order by t", $id, $begin_ts, $end_ts);
    global $db;
    $dbresult = mysql_query($sql, $db) or die("Invalid query: " . mysql_error());
    $idresult = array();
    //parse data
    while (list($timeStamp, $keyName, $keyValue) = mysql_fetch_row($dbresult)){
        //filter the items. be ware that: the keyName is not exact the same as item
        list($type,$skeyName) = filterJudgeType($keyName,$selectedkeys);
        if ("undefined" != $type){
            if( !isSet($idresult[$skeyName]) ){
                $idresult[$skeyName] = array();
                $idresult[$skeyName]["type"] = $type;
            }
            if(!isSet($idresult[$skeyName][$keyName])){
                $idresult[$skeyName][$keyName] = array();
            }
            $idresult[$skeyName][$keyName][$timeStamp] = $keyValue;
        }
    }
    return $idresult;
}

?>

