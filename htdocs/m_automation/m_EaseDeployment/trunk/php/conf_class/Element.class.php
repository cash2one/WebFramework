<?php
header("Content-type: text/html; charset=utf-8");

class Element {
	var $element = "";
	var $name = "";
	var $desc = "";
	var $cmd = "";
	var $cmdSep = "";
	var $ignoreFail = "";
	var $hostName = "";
	var $checkBeforeRun = "";
	var $checkAfterRun = "";


	// constructor
	function Element($element) {
		$this->setElement($element);
	}

	// set elementName
	function setElement($element) {
		$this->element = $element;
	}
    
    function setValue($key,$strValue) {
        $key = trim($key);
        $strValue = trim($strValue);
		switch($key) {
		    case "name" :
			    $this->setName($strValue);
				break;
		    case "desc" :
			    $this->setDesc($strValue);
			    break;
			case "cmd" :
				$this->setCmd($strValue);
                //echo $strValue;
				break;
			case "cmd.sep" :
				$this->setCmdSep($strValue);
				break;
			case "ignore_fail" :
				$this->setIgnoreFail($strValue);
				break;
			case "hostname" :
				$this->setHostname($strValue);
				break;
			case "check_before_run" :
				$this->setCheckBeforeRun($strValue);
				break;
		    case "check_after_run" :
				$this->setCheckAfterRun($strValue);
				break;
			default :
				break;
		}
    }

	// set name
	function setName($name) {
		$this->name = $name;
	}

	function setDesc($desc) {
		$this->desc = $desc;
	}

	function setCmd($cmd) {
		$this->cmd = $cmd;
	}

	function setCmdSep($cmdSep) {
		$this->cmdSep = $cmdSep;
	}

	function setIgnoreFail($ignoreFail) {
		$this->ignoreFail = $ignoreFail;
	}

	function setHostName($hostName) {
		$this->hostName = $hostName;
	}

	function setCheckBeforeRun($checkBeforeRun) {
		$this->checkBeforeRun = $checkBeforeRun;
	}

	function setCheckAfterRun($checkAfterRun) {
		$this->checkAfterRun = $checkAfterRun;
	}

	function getElement() {
		return $this->element;
	}

	// get name
	function getName() {
		return $this->name;
	}

	function getDesc() {
		return $this->desc;
	}

	function getCmd() {
		return $this->cmd;
	}

	function getCmdSep() {
		return $this->cmdSep;
	}

	function getIgnoreFail() {
		return $this->ignoreFail;
	}

	function getHostName() {
		return $this->hostName;
	}

	function getCheckBeforeRun() {
		return $this->checkBeforeRun;
	}

	function getCheckAfterRun() {
		return $this->checkAfterRun;
	}

	// convert class information to a formatting string
	function write() {
		$strElement = "{\n";
		$prefix = "    ".$this->getElement();
		if ($this->getName() != "")
			$strElement .= $prefix.".name: " . $this->getName() . "\n";
		if ($this->getDesc() != "")
			$strElement .= $prefix.".desc: " . $this->getDesc() . "\n";
		if ($this->getCmd() != "")
			$strElement .= $prefix.".cmd: " . $this->getCmd()."\n";
		if ($this->getCmdSep() != "")
			$strElement .= $prefix.".cmd.sep: " . $this->getCmdSep()."\n";
		if ($this->getIgnoreFail() != "")
			$strElement .= $prefix.".ignore_fail: " . $this->getIgnoreFail()."\n";
		if ($this->getHostName() != "")
			$strElement .= $prefix.".hostname: " . $this->getHostName()."\n";
		if ($this->getCheckBeforeRun() != "")
			$strElement .= $prefix.".check_before_run: " . $this->getCheckBeforeRun()."\n";
		if ($this->getCheckAfterRun() != "")
			$strElement .= $prefix.".check_after_run: " . $this->getCheckAfterRun()."\n";
		$strElement .= "}\n";
		return $strElement;
	}

	// parse out class information from a string
	function parse($strElement) {
		if (trim($strElement) != "") {
            $key = "";
			$ElementArray = explode('\nline_split', $strElement);
			foreach ($ElementArray as $str) {
				$str = trim($str);
                if ((string)strpos($str,$this->getElement()) == "0"){
				    $tmpArray = explode('.',$str,2);
					$strArray = explode(':',$tmpArray[1],2);
                    $key = $strArray[0];
                    $value = $strArray[1];
				} else{
                    $value = $value . "\n" . $str; 
                }
                $this->setValue($key,$value);
			}
		}
        //echo $this->getCmd();
	}

	// convert multiple Elements information to a formatting string
	public static function writeList($ElementArray,$element) {
		$ElementStr = "### ".$element." definitions \n";
		foreach ($ElementArray as $Element) {
			$ElementStr .= $Element->write() . "\n";
		}
		return $ElementStr;
	}

	// parse out multiple elements information from the input string
	public static function parseList($ElementListStr,$element){
		$ElementList = array ();
		$ElementStrArray = explode("\\nmodule_split", $ElementListStr);
		foreach ($ElementStrArray as $ElementStr) {
			if (stripos($ElementStr,".name") || stripos($ElementStr,".ignore_fail")) {
				$reflect = new ReflectionClass(ucfirst($element));
				$Element = $reflect->newInstance();
				$Element->parse($ElementStr);
				array_push($ElementList,$Element);
			}
		}
		return $ElementList;
	}

}

?>
