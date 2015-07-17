<?php
require_once "ExceptionResult.class.php";
require_once "WhiteList.class.php";
class ExceptionFilter {
  var $whitelist;
  var $listArray;
  var $resultList;
  function ExceptionFilter($whitelist){
   // echo 'constructor<hr/>.';
    $this->whitelist = new WhiteList();
    $this->listArray = $this->whitelist->load($whitelist);
    /*
    foreach($this->listArray as $a) {
        echo "<br/>$a";
    }
    */
   // echo $this->listArray[0];
    $this->resultList = array();
  }
  //传进来的file文件为经过sed过滤出来的所有含有Exception或者Error的行，存储方式为一行行号一行内容 
  function getFilterResult($file){
   // echo 'in file is :'.$file.'<hr/>';
    if(file_exists($file)){
      $handle = fopen($file,"r");
      while(!feof($handle)){
        $line = trim(fgets($handle));
       // echo "orig line is:".$line;
        if($line =="")
          continue;
        if(is_numeric($line)){
          $rowNum = $line;//是行号则先保存行号
          $line = trim(fgets($handle));//取出该行号对应的内容
         // echo "orig line is:".$line;
          if(!$this->isInWhitelist($line)) {//不在白名单中的异常
            $exceptionResult = new ExceptionResult();
            $exceptionResult->setRowNum($rowNum);
            $exceptionResult->setContent($line);
            array_push($this->resultList,$exceptionResult->toArray());
          }
        }else {//异常的行
          $exceptionResult = new ExceptionResult();
          $exceptionResult->setRowNum('unmark');
          $exceptionResult->setContent($line);
          array_push($this->resultList,$exceptionResult->toArray());
        }
      }//end of while
      fclose($handle);
      return $this->resultList;
    }
    return 'file not exist';
  }
  //当此行的内容在白名单中时，返回true，否则返回false
  function isInWhitelist($line){
    $result = $this->listArray;
    foreach ($result as $entry){
      $pattern = $entry;
      //$line与白名单中的某一个模式匹配成功则返回True
      if(preg_match($pattern,$line)){
     //   echo '<hr/>preg_match '.$pattern.' WITH '.$line.' ;result is True.<hr/>';
        return True;
      }
    }
    //一个都没匹配上，返回false
   // echo '<hr/>preg_match '.$pattern.' WITH '.$line.' ;result is False.<hr/>';
    return False;
  }
}

//$ef = new ExceptionFilter("5");

?>
