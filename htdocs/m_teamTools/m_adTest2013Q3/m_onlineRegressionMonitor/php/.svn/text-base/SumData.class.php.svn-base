<?php
header("Content-type: text/html; charset=utf-8");
require_once "MonitorDB.class.php";

class SumData {
    var $id;
    var $time;
    var $type;
    var $pvNum;
    var $bidNum;
    var $imprNum;
    var $prodName;
    var $version;
    var $ts;

    //constructor
    function SumData() {
    }
    function getId() {
        return $this->id;
    }
    function getTime() {
        return $this->time;
    }
    function getType() {
        return $this->type;
    }
    function getPvNum() {
        return $this->pvNum;
    }
    function getBidNum() {
        return $this->bidNum;
    }
    function getImprNum() {
        return $this->imprNum;
    }
    function getProdName() {
        return $this->prodName;
    }
    function getVersion() {
        return $this->version;
    }
    function getTs() {
        return $this->ts;
    }
    function setId($id) {
        $this->id = $id;
    }
    function setTime($time) {
        $this->time = $time;
    }
    function setType($type) {
        $this->type = $type;
    }
    function setPvNum($pvNum) {
        $this->pvNum = $pvNum;
    }
    function setBidNum($bidNum) {
        $this->bidNum = $bidNum;
    }
    function setImprNum($imprNum) {
        $this->imprNum = $imprNum;
    }
    function setProdName($prodName) {
        $this->prodName = $prodName;
    }
    function setVersion($version) {
        $this->version = $version;
    }
    function setTs($ts) {
        $this->ts = $ts;
    }
    function getBP() {
        if ($this->pvNum ==0)
            return "N/A";
        else    
            return substr($this->bidNum/$this->pvNum,0,6);
    }
    function getIB() {
        if ($this->bidNum == 0)
            return "N/A";
        else    
            return substr($this->imprNum/$this->bidNum,0,6);
    }
    function toArray() {
        $dataArray = array(
                           #"id"=>$this->getId(),
                           "time"=>$this->getTime(),
                           "type"=>$this->getType(),
                           "pvNum"=>$this->getPvNum(),
                           "bidNum"=>$this->getBidNum(),
                           "imprNum"=>$this->getImprNum(),
                           #"prodName"=>$this->getProdName(),
                           #"version"=>$this->getVersion(),
                           #"ts"=>$this->getTs(),
                           "bid/pv"=>$this->getBP(),
                           "impr/bid"=>$this->getIB()
        );
        return $dataArray;
    }

}
?>
