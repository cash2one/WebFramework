<?php

require_once("ExceptionFilter.class.php");
//通过白名单过滤日志中的异常，并将不在白名单内的异常的行号和内容打印出来
$host = $_GET["host"];
$path = $_GET["path"];
$whitelistName = $_GET["whitelist"];

//echo 'host is:'.$host.'<hr/>path is: '.$path.'<hr/>whitelist is:'.$whitelistName;
//$host="qt104";
//$path="/disk3/lihy/convtrack/resin-3.0.21/logs/log";
//$whitelistName="union_stderr_whitelist";


function filter($host,$path,$whitelistName){
    $whitelist_prefix = "/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_smallTools/m_exceptionFilter/data/";
   // echo $whitelist_prefix;
    $fileName = getName();
   // echo $fileName."<hr/>";
    //将文件sed处理后放到该磁盘的test/exceptionFilter目录下
    $sedToPath = getSedToPath($path);
    $mkdirCmd = "ssh ".$host." \"mkdir -p ".$sedToPath."\"";
   // echo $mkdirCmd."<hr>";
    system($mkdirCmd);
    //对文件进行一遍sed过滤，将所有Exception和Error所在的行的行号以及内容存储到tmpE.log中
    $filterCmd = "ssh ".$host." \" sed -n -e '/Exception/I {=;p;b}' -e '/Error/I {=;p}' ".$path." > ".$sedToPath.$fileName." \"";
   // echo $filterCmd."<hr/>";
    system($filterCmd);
    //将数据拷贝到本地data目录下
    $copyCmd = "scp ".$host.":".$sedToPath.$fileName." ".$whitelist_prefix;
   // echo $copyCmd."<hr>";
    system($copyCmd);
    //删掉原机器上的tmp文件
    $deleteCmd = "ssh ".$host." \" rm ".$sedToPath.$fileName." \"";
   // echo $deleteCmd."<hr>";
    system($deleteCmd);
    $inFile = $whitelist_prefix .$fileName;
   // $whitelist = $whitelist_prefix .$whitelistName;
   // echo 'white list :'.$whitelist;
    if($whitelistName != ""){
     //   echo 'new a ExceptionFilter<hr/>';
        $exceptionFilter = new ExceptionFilter($whitelistName);
       // echo 'start to get filter result...<hr/>';
        $result = $exceptionFilter->getFilterResult($inFile);
        $deleteDataCmd = "rm ../data/".$fileName;
        system($deleteDataCmd);
        //echo $result;
        return $result; 
    }else{
        echo 'whitelist:'.$whitelistName.' is not exist.';
        $deleteDataCmd = "rm ../data/".$fileName;
        system($deleteDataCmd);
    }
}

function getName(){
    $fileName = time();
   // echo '<hr/>fileName is '.$fileName;
    return $fileName;
}

function getSedToPath($path){
    $disk = substr($path,0,7);//获取字符串“'/diskX//'
    return $disk.'test/exceptionFilter/';
}

$res = filter($host,$path,$whitelistName);
//echo $res;
echo json_encode($res);
?>



