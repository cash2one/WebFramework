<?php
include("dbUtil.php");
class WhiteList{
  var $file;
  var $whiteListArray;
  function WhiteList(){
      $this->whiteListArray = array();
  }
  function load($whitelistName){
      $this->whiteListArray = dbUtil(GetWhitelistContents, array($whitelistName));
      return $this->whiteListArray;
  }
}
/*
$li = new WhiteList();
$ret = $li->load("5");
foreach($ret as $a) {
echo "<br/>$a";
};
*/
?>
