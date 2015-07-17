<?php
header("Content-type: text/html; charset=utf-8");
require_once "DataHandle.class.php";
//将远程文件拷贝到本地，然后将此文件的数据存入数据库。
function getFileAndSave($dataFile,$prodName,$version,$ts) {
    $dataHandle = new DataHandle();
    if( file_exists($dataFile)) {
        $handle = fopen($dataFile,"r");
        $line = fgets($handle);
        $sumEadPvNum =0;
        $sumEadBidNum = 0;
        $sumEadImprNum = 0;
        $sumExchangePvNum = 0; 
        $sumExchangeBidNum = 0;
        $sumExchangeImprNum = 0;
        while($line != "") {
            //将数据存入数据库 
            $line = str_replace(";",",",$line);
            $lineArray =  explode(",",$line);
            $time = $lineArray[0];
            $platform = $lineArray[1];
            $type = $lineArray[2];
            $pvNum = $lineArray[3];
            $bidNum = $lineArray[4];
            $imprNum = $lineArray[5];
            $id=$dataHandle->insertDataInfo($time,$platform,$type,$pvNum,$bidNum,$imprNum,$prodName,$version,$ts);
            echo "insert dataInfo: ".$id;
            if($type == "ead") {
                $sumEadPvNum += $pvNum;
                $sumEadBidNum += $bidNum;
                $sumEadImprNum += $imprNum;
            }else { //exchange
                $sumExchangePvNum += $pvNum;
                $sumExchangeBidNum += $bidNum;
                $sumExchangeImprNum += $imprNum;
            }
            $line = fgets($handle);
        }
        $id1=$dataHandle->insertSumData($time,"ead",$sumEadPvNum,$sumEadBidNum,$sumEadImprNum,$prodName,$version,$ts);
        echo "insert ead sumData: ".$id1;
        $id2=$dataHandle->insertSumData($time,"exchange",$sumExchangePvNum,$sumExchangeBidNum,$sumExchangeImprNum,$prodName,$version,$ts);
        echo "insert exchange sumData: ".$id1;
        echo "insert done.<br/>";
        $dataHandle->closeDB();
        fclose($handle);
    }
    return 0;
}

$file=$argv[1];
$prod=$argv[2];
$ver=$argv[3];
$t=$argv[4];
/*$file="201312011709.txt";
$prod="Bs";
$ver="test-3";
$t="2013-11-29 15:28";
*/
$prefix="/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_teamTools/m_adTest2013Q3/m_onlineRegressionMonitor/mock/";
$fileName=$prefix.$file;
echo "file prodName version ts is <br/>".$fileName."||".$prod."||".$ver."||".$t."<br/>";
getFileAndSave($fileName,$prod,$ver,$t);
#getFileAndSave("../mock/201312011649.txt","Bs","test-3","2013-11-29 17:00");
?>
