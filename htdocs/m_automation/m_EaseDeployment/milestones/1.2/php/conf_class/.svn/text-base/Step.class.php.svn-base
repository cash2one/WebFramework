<?php
header("Content-type: text/html; charset=utf-8");

require_once "Element.class.php";

class Step extends Element{

	var $moduleName = "";

	// constructor
	function Step() {
		parent::Element("step");
	}

	// set name
	function setName($name) {
		$this->name = $name;
		$nameArray = explode(".", $name);
		$this->moduleName = trim($nameArray[0]);
	}

	function getModuleName() {
		return $this->moduleName;
	}


	public static function getStepsbyModule($moduleName,$stepList){
		$returnList = array();
		foreach($stepList as $step){
			if ($step->getModuleName() == $moduleName)
				array_push($returnList,$step);
		}
		return $returnList;
	}

}

################## test code ##########################

/*$Step = new Step();
$Step->parse("check_out_anti_code:     checkout $anti_code$ to dir $war_dir$");
echo $Step->name . "</br>";
echo $Step->value . "</br>";
$Step->set("hello", "world-pease");
echo $Step->write() . "</br>";*/

################## test code ##########################
?>