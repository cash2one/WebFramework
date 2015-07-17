<?php

require_once("DataHandle.class.php");
define("STATUSFILE","/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_teamTools/m_adTest2013Q3/m_onlineRegressionMonitor/status.txt");
define("COMMANDFILE","/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_teamTools/m_adTest2013Q3/m_onlineRegressionMonitor/command.txt");
define("RUN","ssh nb014 \"cd /disk1/lihy/;python mfsStat.py &\"");
define("STOP","ssh nb014 \"pkill -f mfsStat.py\"");

function getStatus() {
    $file = fopen(STATUSFILE,"r");
    $line = fgets($file);
    fclose($file);
    $line=trim($line);
    if($line == "running") {
        return "running";
    }else {
        return "stopped";
    }
}

function runTool($prodName,$version,$ts){
    $status = getStatus();
    if($status != "running") {
        setProdInfo($prodName,$version,$ts);
        system("echo running > ../status.txt");
        system(RUN);
        return TRUE;
    } else {
        echo "<br/>Already start. no need to start again.<br/>";
        return FALSE;
    }
}

function setProdInfo($prodName,$version,$ts){
    system("echo ".$prodName." > ../prodInfo.txt");
    system("echo ".$version." >> ../prodInfo.txt");
    system("echo ".$ts." >> ../prodInfo.txt");
    return;
}
function getRow($prodName,$version,$ts){
    $dataHandle = new DataHandle();
    $result = $dataHandle->getOneMinuteSumData($prodName,$version,$ts);
    $dataHandle->closeDB();
    return $result;
}

function getAllSumData($prodName,$version,$ts){
    $dataHandle = new DataHandle();
    $result = $dataHandle->getAllSumData($prodName,$version,$ts);
    $dataHandle->closeDB();
    return $result;
}
function getDetailData($prodName,$version,$ts,$ts_start_end_str,$sortCol,$sortType){
    $dataHandle = new DataHandle();
    $result = $dataHandle->getOneMinuteData($prodName,$version,$ts,$ts_start_end_str,$sortCol,$sortType);
    $dataHandle->closeDB();
    return $result;
}

function getAllDetailData($prodName,$version,$ts){
    $dataHandle = new DataHandle();
    $result = $dataHandle->getAllDetailData($prodName,$version,$ts);
    $dataHandle->closeDB();
    return $result;
}

function getAllData() {
    $dataHandle = new DataHandle();
    $result = $dataHandle->getAllData();
    $dataHandle->closeDB();
    return $result;
}

function stopTool() {
    if(getStatus()=="running"){
        //当脚本未执行态的时候才能执行stop命令
        echo "wait for command stop ...<br>";
        system(STOP);
        system("echo stopped > ../status.txt");
        echo "stopped...";
        return TRUE;
    } else {
        echo "<br/>Already stoped, no need to stop again.<br/>";
        return FALSE;
    }
}
?>
