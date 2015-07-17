<?php
header("Content-type: text/html; charset=utf-8");
require_once "MonitorDB.class.php";
require_once "DataInfo.class.php";
require_once "SumData.class.php";
class DataHandle{
    var $monitorDB;
    var $prodName;
    var $version;
    var $ts;
    
    //constructor
    function DataHandle() {
        $this->monitorDB = new MonitorDB();
    }
    function closeDB() {
        $this->monitorDB->close();
    }
    //插入一条数据，返回id
    function insertDataInfo($time,$platform,$type,$pvNum,$bidNum,$imprNum,$prodName,$version,$ts) {
        $id = $this->monitorDB->insertDataInfo($time,$platform,$type,$pvNum,$bidNum,$imprNum,$prodName,$version,$ts);
       // echo "DataHandle:insert into dataInfo:".$id;
        return $id;
    }
    //汇总数据，将一分钟的汇总数据存到sumData表中
    function insertSumData($time,$type,$pvNum,$bidNum,$imprNum,$prodName,$version,$ts) {
        $id = $this->monitorDB->insertSumData($time,$type,$pvNum,$bidNum,$imprNum,$prodName,$version,$ts);
        return $id;
    }
    //获取所有对比数据
    function getAllSumData($prodName,$version,$ts) {
        $query = "SELECT id,time,type,pvNum,bidNum,imprNum,prodName,version,ts FROM sumData WHERE prodName=\"".$prodName."\" AND version=\"".$version."\" AND ts=\"".$ts."\" ORDER BY id DESC";

        $result = $this->monitorDB->execSQL($query);
        $arrayList = array();
        $sumData;
        $title=array( "time"=>"time", "type"=>"type", "pvNum"=>"pvNum","bidNum"=>"bidNum","imprNum"=>"imprNum","bid/pv"=>"bid/pv","impr/bid"=>"impr/bid");
        array_push($arrayList,$title);
        foreach ($result as $entry) {
            $sumData = new SumData();
            $sumData->setId($entry["id"]);
            $timeStr=$entry["time"];
            $sumData->setTime("<a target=_blank href='./viewDetail.php?info=".$prodName.":".$version.":".$ts.":".$timeStr."'>".$timeStr."</a>");
            $sumData->setType($entry["type"]);
            $sumData->setPvNum($entry["pvNum"]);
            $sumData->setBidNum($entry["bidNum"]);
            $sumData->setImprNum($entry["imprNum"]);
            $sumData->setProdName($entry["prodName"]);
            $sumData->setVersion($entry["version"]);
            $sumData->setTs($entry["ts"]);
            array_push($arrayList,$sumData->toArray());
        }
        return $arrayList;
    }
    function getOneMinuteSumData($prodName,$version,$ts) {
       // $this->getFileAndSave($prodName,$version,$ts);
        $time = $this->getLastMinuteString(2);
        $query = "SELECT id,time,type,pvNum,bidNum,imprNum,prodName,version,ts FROM sumData WHERE time=\"".$time."\" AND prodName=\"".$prodName."\" AND version=\"".$version."\" AND ts=\"".$ts."\"";
# AND ts=\"".$ts."\"";
       # echo "<br/>sql is :".$query;
        $result = $this->monitorDB->execSQL($query);
        $arrayList = array();
        $title= array("time"=>"time", "type"=>"type", "pvNum"=>"pvNum","bidNum"=>"bidNum","imprNum"=>"imprNum","bid/pv"=>"bid/pv","impr/bid"=>"impr/bid");
        array_push($arrayList,$title);
        $sumData;
        foreach ($result as $entry) {
            $sumData = new SumData();
            $sumData->setId($entry["id"]);
            $sumData->setTime($entry["time"]);
            $sumData->setType($entry["type"]);
            $sumData->setPvNum($entry["pvNum"]);
            $sumData->setBidNum($entry["bidNum"]);
            $sumData->setImprNum($entry["imprNum"]);
            $sumData->setProdName($entry["prodName"]);
            $sumData->setVersion($entry["version"]);
            $sumData->setTs($entry["ts"]);
            array_push($arrayList,$sumData->toArray());
        }
        return $arrayList;
    }
    //获取所有数据
    function getAllDetailData($prodName,$version,$ts) {
        $query = "SELECT * FROM dataInfo WHERE prodName=\"".$prodName."\" AND version=\"".$version."\" AND ts=\"".$ts."\" ORDER BY id DESC";
# AND ts=\"".$ts."\" ORDER BY id DESC";
        $result = $this->monitorDB->execSQL($query);
        $arrayList = array();
        $title = array("time"=>"time","platform"=>"platform", "type"=>"type", "pvNum"=>"pvNum","bidNum"=>"bidNum","imprNum"=>"imprNum","bid/pv"=>"bid/pv","impr/bid"=>"impr/bid");
        array_push($arrayList,$title);
        $dataInfo;
        foreach ($result as $entry) {
            $dataInfo = new DataInfo();
            $dataInfo->setId($entry["id"]);
            $dataInfo->setTime($entry["time"]);
            $dataInfo->setPlatform($entry["platform"]);
            $dataInfo->setType($entry["type"]);
            $dataInfo->setPvNum($entry["pvNum"]);
            $dataInfo->setBidNum($entry["bidNum"]);
            $dataInfo->setImprNum($entry["imprNum"]);
            $dataInfo->setProdName($entry["prodName"]);
            $dataInfo->setVersion($entry["version"]);
            $dataInfo->setTs($entry["ts"]);
            array_push($arrayList,$dataInfo->toArray());
        }       
        return $arrayList;
    }
    //获取所有数据库中的数据
    function getAllData() {
        $query = "SELECT * FROM dataInfo ORDER BY id DESC";
       // echo $query;
        $result = $this->monitorDB->execSQL($query);
        $arrayList = array();
        $dataInfo;
        foreach ($result as $entry) {
            $dataInfo = new DataInfo();
            $dataInfo->setId($entry["id"]);
            $dataInfo->setTime($entry["time"]);
            $dataInfo->setPlatform($entry["platform"]);
            $dataInfo->setType($entry["type"]);
            $dataInfo->setPvNum($entry["pvNum"]);
            $dataInfo->setBidNum($entry["bidNum"]);
            $dataInfo->setImprNum($entry["imprNum"]);
            $dataInfo->setProdName($entry["prodName"]);
            $dataInfo->setVersion($entry["version"]);
            $dataInfo->setTs($entry["ts"]);
            array_push($arrayList,$dataInfo->toArray());
        }       
        return $arrayList; 
    }
    function getLastMinuteString($seq) {
         //获取之前处理的一分钟时间格式
        # $time=time();
        # $time=$time-86400-660;
        # $dataString = date("Y",$time) .date("m",$time) .date("d",$time) .date("H",$time) .date("i",$time) ."00-" .date("Y",$time) .date("m",$time) .date("d",$time) .date("H",$time) .date("i",$time+60) ."00";
        $string = system("tail -".$seq." ../run.log | head -1");
        $dataString = system("tail -1 ../mock/".$string." | awk -F , '{printf $1}'");
        return $dataString;
    }
    //获取所有最后一分钟的所有数据
    function getOneMinuteData($prodName,$version,$ts,$ts_start_end_str,$sortName,$sortType) {
        $time = $ts_start_end_str;
        $query = "SELECT * FROM dataInfo WHERE time=\"".$time."\" AND prodName=\"".$prodName."\" AND version=\"".$version."\" AND ts=\"".$ts."\"";#
        # ORDER BY id DESC";
        if ($sortName == "") {
            $query = $query." ORDER BY id DESC";
        }else {
            if($sortType == 1)
                $query = $query." ORDER BY ".$sortName." ASC";
            else
                $query = $query." ORDER BY ".$sortName." DESC";
        }
        #echo "getOneMinuteData:".$query."<br/>";
        $result = $this->monitorDB->execSQL($query);
        $arrayList = array();
        $dataInfo;
        $pvNumStr = "<a href='./viewDetail.php?info=".$prodName.":".$version.":".$ts.":$ts_start_end_str&sortName=pvNum&sortType=".$sortType."'>pvNum</a>";
        $bidNumStr = "<a href='./viewDetail.php?info=$prodName:$version:$ts:$ts_start_end_str&sortName=bidNum&sortType=$sortType'>bidNum</a>";
        $imprNumStr = "<a href='./viewDetail.php?info=$prodName:$version:$ts:$ts_start_end_str&sortName=imprNum&sortType=$sortType'>imprNum</a>";
        $title=array("time"=>"time","platform"=>"platform","type"=>"type","pvNum"=>$pvNumStr,"bidNum"=>$bidNumStr,"imprNum"=>$imprNumStr,"bid/pv"=>"bid/pv","impr/bid"=>"impr/bid");
        array_push($arrayList,$title);
        foreach ($result as $entry) {
            $dataInfo = new DataInfo();
            $dataInfo->setId($entry["id"]);
            $dataInfo->setTime($entry["time"]);
            $dataInfo->setPlatform($entry["platform"]);
            $dataInfo->setType($entry["type"]);
            $dataInfo->setPvNum($entry["pvNum"]);
            $dataInfo->setBidNum($entry["bidNum"]);
            $dataInfo->setImprNum($entry["imprNum"]);
            $dataInfo->setProdName($entry["prodName"]);
            $dataInfo->setVersion($entry["version"]);
            $dataInfo->setTs($entry["ts"]);
            array_push($arrayList,$dataInfo->toArray());
        }
        return $arrayList;
    }
}

//for test
//测试存数据
/*
$handle=new DataHandle();
$handle->insertSumData("20131127195600-20131127195700","ead",100,100,100);

//$handle->getFileAndSave("Bs","test-1","2013-11-27 11:00");
$result=$handle->getOneMinuteData("Bs","test-1","2013-11-27 11:00");
foreach ($result as $entry) {
    echo $entry["id"]."||".$entry["time"]."||".$entry["platform"]."||".$entry["type"]."||".$entry["pvNum"]."||".$entry["bidNum"]."||".$entry["imprNum"]."||".$entry["isComparison"];
    echo "<br/>";
}
*/
?>
