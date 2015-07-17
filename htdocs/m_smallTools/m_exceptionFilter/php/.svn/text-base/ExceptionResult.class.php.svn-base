<?php
header("Content-type: text/html; charset=utf-8");

class ExceptionResult {
  var $rowNum;
  var $content;

  function ExceptionResult() {
  }

  function getRowNum() {
    return $this->rowNum;
  }
  function getContent() {
    return $this->content;
  }
  function setRowNum($rowNum) {
    $this->rowNum = $rowNum;
  }
  function setContent($content) {
    $this->content = $content;
  }

  function toArray() {
    $dataArray = array("rowNum"=>$this->getRowNum(),"content"=>$this->getContent());
    return $dataArray;
  }
}
?>
