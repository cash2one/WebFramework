<?php
header("Content-type: text/html; charset=utf-8");
define("MONITORDB", "../mock/monitorData.sqlite");

class MonitorDB{
    var $db;

    //constructor
    function MonitorDB(){
        $this->db = sqlite_open(MONITORDB, 0666, $sqliteerror);
        if (!$this->db){
            die($sqliteerror);
        }
        date_default_timezone_set("Asia/Shanghai");
    }
    //关闭与数据库的链接    
    function close(){
        sqlite_close($this->db);
    }

    //创建表,dataInfo用来保存所有的统计数据，isComparison字段用来标记历史比对数据
    public function createTables(){
        sqlite_query($this->db, 'CREATE TABLE dataInfo(id INTEGER PRIMARY KEY NOT NULL UNIQUE,time VARCHAR NOT NULL ,platform VARCHAR NOT NULL,type VARCHAR, pvNum INTEGER DEFAULT 0, bidNum INTEGER DEFAULT 0,imprNum INTEGER DEFAULT 0,prodName VARCHAR NOT NULL,version VARCHAR NOT NULL,ts VARCHAR)');
        sqlite_query($this->db, 'CREATE TABLE sumData(id INTEGER PRIMARY KEY NOT NULL UNIQUE,time VARCHAR NOT NULL,type VARCHAR,pvNum INTEGER DEFAULT 0,bidNum INTEGER DEFAULT 0,imprNum INTEGER DEFAULT 0,prodName VARCHAR NOT NULL,version VARCHAR NOT NULL,ts VARCHAR)');
    }

    public function insertDataInfo($time,$platform,$type,$pvNum,$bidNum,$imprNum,$prodName,$version,$ts){
        $query = "INSERT INTO  dataInfo (time, platform, type, pvNum, bidNum, imprNum, prodName, version, ts) values (\"".$time ."\", \"".$platform."\", \"".$type."\", \"".$pvNum."\", \"".$bidNum."\",\"".$imprNum."\",\"".$prodName."\",\"".$version."\",\"".$ts."\")";
        $result = sqlite_query($this->db,$query);
        $id = sqlite_last_insert_rowid($this->db);
        return $id;
    }
    //汇总一分钟的数据，分type存。将汇总数据存到sumData表中
    public function insertSumData($time,$type,$pvNum,$bidNum,$imprNum,$prodName,$version,$ts){
        $query = "INSERT INTO  sumData (time, type, pvNum, bidNum, imprNum, prodName, version, ts) values (\"".$time ."\", \"".$type."\", \"".$pvNum."\", \"".$bidNum."\",\"".$imprNum."\",\"".$prodName."\",\"".$version."\",\"".$ts."\")";
        $result = sqlite_query($this->db,$query);
        $id = sqlite_last_insert_rowid($this->db);
        return $id;        
    }
    //执行输入的sql语句
    public function execSQL($query) {
        $result = sqlite_query($this->db,$query);
        return sqlite_fetch_all($result,SQLITE_ASSOC);
    }
}

#######TEST#####
/*
$monitorDB = new MonitorDB();
$monitorDB->createTables();
$monitorDB->insertDataInfo("20131118100100-20131118100200","hs031_1","exchage","120","120","102","Bs","m1.0.0","201312011845");
$monitorDB->insertSumData("20131118100100-20131118100200","exchage","1200","1200","1000","Bs","m1.0.0","201312011845");
$monitorDB->insertSumData("20131118100100-20131118100200","ead","1201","1201","1001","Bs","m1.0.0","201312011845");
$monitorDB->close();
*/
?>
